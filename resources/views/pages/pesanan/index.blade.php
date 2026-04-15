@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Daftar Pesanan Lunas Masuk</h4>
                        <a href="{{ route('user.pesanan.scanner') }}" class="btn btn-primary btn-icon-text">
                            <i class="mdi mdi-qrcode-scan btn-icon-prepend"></i> Scan QR Code 
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th> No. Order </th>
                                    <th> Nama Pembeli (Guest) </th>
                                    <th> Detail Pesanan </th>
                                    <th> Total Pendapatan </th>
                                    <th> Tanggal </th>
                                    <th> Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan as $p)
                                    <tr>
                                        <td> <span class="badge badge-info">{{ $p->midtrans_order_id }}</span> </td>
                                        <td> {{ $p->guest->nama_guest ?? 'Guest' }} </td>
                                        <td>
                                            <ul style="padding-left: 15px; margin-bottom: 0;">
                                                @foreach ($p->detailPesanan as $detail)
                                                    <li>{{ $detail->menu->nama_menu ?? 'Terhapus' }} ({{ $detail->jumlah }}x)</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="font-weight-bold text-success"> 
                                            Rp {{ number_format($p->total, 0, ',', '.') }} 
                                        </td>
                                        <td> {{ $p->created_at->format('d M Y, H:i') }} </td>
                                        <td> 
                                            <label class="badge badge-success">
                                                {{ ucfirst($p->status_bayar) }}
                                            </label> 
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection