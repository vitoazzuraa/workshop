@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pesanan Lunas Masuk</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> No. Order </th>
                                <th> Nama Guest </th>
                                <th> Total Bayar </th>
                                <th> Tanggal </th>
                                <th> Status </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <td> {{ $p->midtrans_order_id }} </td>
                                <td> {{ $p->idguest }} </td>
                                <td> Rp {{ number_format($p->total, 0, ',', '.') }} </td>
                                <td> {{ $p->created_at->format('d M Y, H:i') }} </td>
                                <td> <label class="badge badge-success">Lunas</label> </td>
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
