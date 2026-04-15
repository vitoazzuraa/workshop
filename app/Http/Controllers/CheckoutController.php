<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Guest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $cartItems = json_decode($request->cart_items, true);
        if (!$cartItems) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong!'], 400);
        }

        $guestId = Session::get('guest_iduser');
        $guestExists = Guest::find($guestId);

        if (!$guestId || !$guestExists) {
            $lastGuest = Guest::latest('idguest')->first();
            $count = $lastGuest ? $lastGuest->idguest + 1 : 1;
            $guestName = 'Guest_' . str_pad($count, 7, '0', STR_PAD_LEFT);

            $guest = Guest::create([
                'nama_guest' => $guestName
            ]);
            
            $guestId = $guest->idguest;
            Session::put('guest_iduser', $guestId);
            Session::put('guest_name', $guestName);
        }

        try {
            DB::beginTransaction();

            $totalBayar = 0;
            foreach ($cartItems as $item) {
                $menu = Menu::find($item['idmenu']);
                if ($menu) {
                    $totalBayar += $menu->harga * $item['jumlah'];
                }
            }

            $orderId = 'TRX-' . time() . '-' . rand(100, 999);

            $pesanan = Pesanan::create([
                'idguest'           => $guestId,
                'total'             => $totalBayar,
                'status_bayar'      => 'pending',
                'midtrans_order_id' => $orderId,
            ]);

            foreach ($cartItems as $item) {
                $menu = Menu::find($item['idmenu']);
                if ($menu) {
                    PesananDetail::create([
                        'idpesanan' => $pesanan->idpesanan,
                        'idmenu'    => $menu->idmenu,
                        'jumlah'    => $item['jumlah'],
                        'harga'     => $menu->harga,
                        'subtotal'  => $menu->harga * $item['jumlah']
                    ]);
                }
            }

            DB::commit();

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $totalBayar,
                ],
                'customer_details' => [
                    'first_name' => Session::get('guest_name'),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken,
                'order_id'   => $orderId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error', 
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success($order_id)
    {
        $pesanan = Pesanan::where('midtrans_order_id', $order_id)->firstOrFail();
        return view('customer.success', compact('pesanan'));
    }
}