@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Detail Validasi Pesanan</h4>
                    <a href="{{ route('user.pesanan.scanner') }}" class="btn btn-light btn-sm">
                        <i class="mdi mdi-keyboard-backspace"></i> Kembali ke Scanner
                    </a>
                </div>

                <div class="border-bottom pb-3 mb-3">
                    <h5 class="text-primary">Info Pelanggan</h5>
                    <p class="mb-1"><strong>Nama Guest:</strong> {{ $pesanan->guest->nama_guest ?? 'Guest' }}</p>
                    <p class="mb-1"><strong>No. Order:</strong> <span class="badge badge-info">{{ $pesanan->midtrans_order_id }}</span></p>
                    <p class="mb-0"><strong>Waktu Bayar:</strong> {{ $pesanan->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="table-responsive mb-4">
                    <h5 class="text-primary">Daftar Menu yang Dipesan</h5>
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->detailPesanan as $detail)
                            <tr>
                                <td>{{ $detail->menu->nama_menu ?? 'Menu Terhapus' }}</td>
                                <td class="text-center">{{ $detail->jumlah }}x</td>
                                <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total Pendapatan Vendor</th>
                                <th class="text-right text-success" style="font-size: 1.1rem;">
                                    Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <div class="alert alert-success d-inline-block">
                        <i class="mdi mdi-check-circle-outline"></i> 
                        <strong>PESANAN VALID:</strong> Silakan proses makanan untuk pelanggan ini.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection