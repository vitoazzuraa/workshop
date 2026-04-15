<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $pesanan = Pesanan::where('midtrans_order_id', $request->order_id)->first();
            
            if ($pesanan) {
                $status = $request->transaction_status;
                
                if ($status == 'capture' || $status == 'settlement') {
                    $pesanan->update([
                        'status_bayar' => 'lunas', 
                        'metode_bayar' => $request->payment_type
                    ]);
                } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                    $pesanan->update(['status_bayar' => 'gagal']);
                }
                
                $pesanan->update(['midtrans_transaction_id' => $request->transaction_id]);
            }
        }
        
        return response()->json(['status' => 'success']);
    }
}