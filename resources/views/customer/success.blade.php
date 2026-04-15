@extends('layouts.guest')

@section('content')
<div class="container text-center mt-5">
    <div class="card shadow-sm p-5 mx-auto" style="max-width: 500px;">
        <i class="mdi mdi-check-circle text-success" style="font-size: 80px;"></i>
        <h2 class="font-weight-bold">Pembayaran Berhasil!</h2>
        <hr>
        <p>Gunakan QR Code di bawah untuk pengambilan pesanan:</p>
        <div class="d-flex justify-content-center mb-3">
            {!! QrCode::size(200)->generate($pesanan->midtrans_order_id) !!}
        </div>
        <h4 class="font-weight-bold">{{ $pesanan->midtrans_order_id }}</h4>
        <a href="{{ route('landing') }}" class="btn btn-gradient-primary mt-4">Pesan Lagi</a>
    </div>
</div>
@endsection