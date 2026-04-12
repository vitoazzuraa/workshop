<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Menu;
use App\Models\Guest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $items = json_decode($request->cart_items, true);
        if (!$items) return back()->with('error', 'Keranjang kosong.');

        DB::beginTransaction();
        try {
            $calculatedTotal = 0;
            $verifiedItems = [];

            foreach ($items as $item) {
                $dbMenu = Menu::where('idmenu', $item['idmenu'])
                              ->where('id', $request->id_user_penyedia)
                              ->first();

                if (!$dbMenu) throw new \Exception("Menu tidak valid atau penyedia tidak cocok.");

                $subtotal = $dbMenu->harga * $item['jumlah'];
                $calculatedTotal += $subtotal;

                $verifiedItems[] = [
                    'idmenu' => $dbMenu->idmenu,
                    'jumlah' => $item['jumlah'],
                    'harga'  => $dbMenu->harga,
                    'subtotal' => $subtotal
                ];
            }

            if (!Session::has('guest_iduser')) {
                $count = Guest::count() + 1;
                $guestName = 'Guest_' . str_pad($count, 7, '0', STR_PAD_LEFT);
                $guest = Guest::create(['nama_guest' => $guestName]);
                Session::put('guest_iduser', $guest->idguest);
                Session::put('guest_name', $guestName);
            }

            $orderId = 'TRX-' . time();

            $pesanan = Pesanan::create([
                'id' => $request->id_user_penyedia,
                'idguest' => Session::get('guest_iduser'),
                'total' => $calculatedTotal,
                'status_bayar' => 'pending',
                'midtrans_order_id' => $orderId
            ]);

            foreach ($verifiedItems as $vItem) {
                PesananDetail::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $vItem['idmenu'],
                    'jumlah' => $vItem['jumlah'],
                    'harga' => $vItem['harga'],
                    'subtotal' => $vItem['subtotal']
                ]);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$calculatedTotal,
                ],
                'customer_details' => [
                    'first_name' => Session::get('guest_name'),
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $pesanan->update(['midtrans_token' => $snapToken]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
