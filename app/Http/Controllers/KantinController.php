<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Guest;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;

class KantinController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('menu')->get();
        return view('kantin.order', compact('vendors'));
    }

    public function menu($idvendor)
    {
        $menus = Menu::where('idvendor', $idvendor)->get();
        return response()->json(['status' => 'success', 'code' => 200, 'data' => $menus]);
    }

    public function pesan(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->input('items');
        $total = 0;

        // Hitung total dari DB agar harga tidak bisa dimanipulasi client
        $menuIds = collect($items)->pluck('idmenu')->unique()->toArray();
        $menus   = Menu::whereIn('idmenu', $menuIds)->get()->keyBy('idmenu');

        foreach ($items as $item) {
            $menu = $menus->get($item['idmenu']);
            if (!$menu) {
                return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Menu tidak valid.']);
            }
            $total += $menu->harga * $item['jumlah'];
        }

        // Generate customer_code: guest_0000001, guest_0000002, dst.
        $count        = Guest::count() + 1;
        $customerCode = 'guest_' . str_pad($count, 7, '0', STR_PAD_LEFT);

        $guest = Guest::create([
            'customer_code' => $customerCode,
            'nama'          => $request->nama,
            'no_hp'         => $request->no_hp ?? null,
        ]);

        // Buat pesanan
        $orderId = 'KANTIN-' . time() . '-' . rand(100, 999);
        $pesanan = Pesanan::create([
            'guest_id'          => $guest->id,
            'nama'              => $request->nama,
            'total'             => $total,
            'metode_bayar'      => 1,
            'status_bayar'      => 0,
            'midtrans_order_id' => $orderId,
        ]);

        // Simpan detail
        foreach ($items as $item) {
            $menu = $menus->get($item['idmenu']);
            $jumlah = (int) $item['jumlah'];
            DetailPesanan::create([
                'idmenu'    => $menu->idmenu,
                'idpesanan' => $pesanan->idpesanan,
                'jumlah'    => $jumlah,
                'harga'     => $menu->harga,
                'subtotal'  => $menu->harga * $jumlah,
                'catatan'   => $item['catatan'] ?? '',
            ]);
        }

        // Request Midtrans Snap token
        \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
        // CURLOPT_HTTPHEADER (10023) harus ada agar SDK kompatibel dengan PHP 8.x
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [],
        ];

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $request->nama,
            ],
            'item_details' => collect($items)->map(function ($item) use ($menus) {
                $menu = $menus->get($item['idmenu']);
                return [
                    'id'       => (string) $menu->idmenu,
                    'price'    => (int) $menu->harga,
                    'quantity' => (int) $item['jumlah'],
                    'name'     => $menu->nama_menu,
                ];
            })->values()->toArray(),
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Hapus pesanan yang sudah dibuat jika Midtrans gagal
            $pesanan->detail()->delete();
            $pesanan->delete();
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Midtrans error: ' . $e->getMessage(),
            ], 500);
        }

        $pesanan->update(['midtrans_snap_token' => $snapToken]);

        return response()->json([
            'status'     => 'success',
            'code'       => 200,
            'message'    => 'Pesanan dibuat.',
            'data'       => [
                'idpesanan'  => $pesanan->idpesanan,
                'snap_token' => $snapToken,
                'client_key' => env('MIDTRANS_CLIENT_KEY'),
            ],
        ]);
    }

    public function status($id)
    {
        $pesanan = Pesanan::with('detail.menu')->findOrFail($id);
        return view('kantin.status', compact('pesanan'));
    }

    public function cekPesanan(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (!$q) {
            return response()->json(['status' => 'error', 'message' => 'Masukkan nomor HP atau ID pesanan.']);
        }

        // Jika semua angka dan pendek → cari sebagai ID pesanan
        if (ctype_digit($q) && strlen($q) <= 9) {
            $pesanan = Pesanan::with('detail.menu')->find((int) $q);
            if (!$pesanan) {
                return response()->json(['status' => 'error', 'message' => 'Pesanan #' . $q . ' tidak ditemukan.']);
            }
            return response()->json(['status' => 'success', 'redirect' => route('kantin.status', $pesanan->idpesanan)]);
        }

        // Cari berdasarkan nomor HP
        $guests = Guest::where('no_hp', $q)->pluck('id');
        if ($guests->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada pesanan dengan nomor HP ' . $q]);
        }

        $pesanans = Pesanan::whereIn('guest_id', $guests)
            ->orderBy('idpesanan', 'desc')
            ->take(10)
            ->get(['idpesanan', 'nama', 'total', 'status_bayar', 'timestamp']);

        $result = $pesanans->map(fn($p) => [
            'id'          => $p->idpesanan,
            'nama'        => $p->nama,
            'total'       => 'Rp ' . number_format($p->total, 0, ',', '.'),
            'status'      => $p->status_bayar ? 'Lunas' : 'Belum Bayar',
            'status_bayar'=> $p->status_bayar,
            'waktu'       => $p->timestamp ? \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y H:i') : '-',
            'url'         => route('kantin.status', $p->idpesanan),
        ]);

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function cekStatus($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return response()->json([
            'status_bayar' => $pesanan->status_bayar,
        ]);
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        if (!$serverKey) {
            return response()->json(['ok' => true]);
        }

        // Verifikasi signature Midtrans
        $orderId       = $request->input('order_id');
        $statusCode    = $request->input('status_code');
        $grossAmount   = $request->input('gross_amount');
        $signature     = $request->input('signature_key');
        $expectedSig   = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signature !== $expectedSig) {
            return response()->json(['ok' => false, 'message' => 'Invalid signature'], 403);
        }

        $txStatus = $request->input('transaction_status');
        if (in_array($txStatus, ['capture', 'settlement'])) {
            Pesanan::where('midtrans_order_id', $orderId)->update(['status_bayar' => 1]);
        }

        return response()->json(['ok' => true]);
    }
}
