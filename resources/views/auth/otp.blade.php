@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-4 mx-auto" style="max-width: 400px;">
        <h4 class="text-center">Verifikasi OTP</h4>
        <p class="text-muted text-center">Masukkan 6 karakter kode yang dikirim ke email Anda</p>
        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <input type="text" name="otp" class="form-control text-center mb-3" maxlength="6" placeholder="------" required autofocus>
            <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
        </form>
    </div>
</div>
@endsection
