@extends('layouts.auth')

@section('content')
<div class="row w-100 mx-0">
  <div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
      <div class="brand-logo text-center">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
      </div>
      <h4 class="text-center">Selamat Datang!</h4>
      <h6 class="font-weight-light text-center">Silakan login untuk masuk ke perpustakaan.</h6>

      <form class="pt-3" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" required>
          @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">MASUK</button>
        </div>

        <div class="text-center mt-4 font-weight-light">
          Atau login lebih cepat dengan:
        </div>

        <div class="mb-2 mt-3">
          <a href="{{ route('google.login') }}" class="btn btn-block btn-danger auth-form-btn">
            <i class="mdi mdi-google me-2"></i> Login with Google
          </a>
        </div>

        <div class="text-center mt-4 font-weight-light">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary">Buat Akun Baru</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
