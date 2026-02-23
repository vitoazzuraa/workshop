@extends('layouts.auth')

@section('content')
<div class="row w-100 mx-0">
  <div class="col-lg-6 mx-auto">
    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
      <div class="brand-logo text-center">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
      </div>
      <h4 class="text-center">Baru di sini?</h4>
      <h6 class="font-weight-light text-center">Daftar sekarang untuk mulai meminjam buku.</h6>

      <form class="pt-3" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
          <input type="text" name="name" class="form-control form-control-lg" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
          @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
          <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" required>
          @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
          @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
          <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Konfirmasi Password" required>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">DAFTAR</button>
        </div>
        <div class="text-center mt-4 font-weight-light">
          Sudah punya akun? <a href="{{ route('login') }}" class="text-primary">Login di sini</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
