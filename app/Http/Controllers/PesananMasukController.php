<?php
namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class PesananMasukController extends Controller
{
    public function index()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (! $vendor) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai Vendor.');
        }

        $pesanan = Pesanan::where('status_bayar', 'lunas')
            ->whereHas('detailPesanan.menu', function ($query) use ($vendor) {
                $query->where('idvendor', $vendor->idvendor);
            })
            ->with(['guest', 'detailPesanan.menu'])
            ->latest()
            ->get();

        return view('pages.pesanan.index', compact('pesanan'));
    }

    public function scanner()
    {
        return view('pages.pesanan.scanner');
    }

    public function periksa($id_dari_scan)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (! $vendor) {
            abort(403, 'Anda tidak terdaftar sebagai Vendor.');
        }

        $pesanan = Pesanan::where('midtrans_order_id', $id_dari_scan)
            ->whereHas('detailPesanan.menu', function ($query) use ($vendor) {
                $query->where('idvendor', $vendor->idvendor);
            })
            ->with(['guest', 'detailPesanan.menu'])
            ->first();

        if (! $pesanan) {
            abort(403, 'MAAF, PESANAN INI TIDAK DITEMUKAN ATAU BUKAN MILIK KANTIN ANDA.');
        }

        return view('pages.pesanan.periksa', compact('pesanan'));
    }
}
