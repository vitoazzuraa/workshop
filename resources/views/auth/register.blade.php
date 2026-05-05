<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Daftar Vendor — Sistem Koleksi Buku</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                        </div>
                        <h4>Daftar sebagai Vendor</h4>
                        <h6 class="font-weight-light">Buat akun untuk mengelola menu kantin.</h6>

                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                            </div>
                        @endif

                        <form action="{{ route('register.store') }}" method="POST" class="pt-3">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="nama_vendor" class="form-control form-control-lg"
                                    placeholder="Nama Warung / Kantin" value="{{ old('nama_vendor') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="Nama Pemilik" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="Email" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-lg"
                                    placeholder="Password (min. 6 karakter)" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                    placeholder="Konfirmasi Password" required>
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    <i class="mdi mdi-account-plus me-1"></i> DAFTAR
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <span class="text-muted small">Sudah punya akun?</span>
                                <a href="{{ route('login') }}" class="text-primary small fw-semibold ms-1">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
</body>
</html>
