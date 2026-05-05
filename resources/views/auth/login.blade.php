<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login — Sistem Koleksi Buku</title>
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
                        <h4>Sistem Koleksi Buku</h4>
                        <h6 class="font-weight-light">Silakan login untuk melanjutkan.</h6>

                        @if(session('success'))
                            <div class="alert alert-success mt-3">{!! session('success') !!}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                            </div>
                        @endif

                        {{-- Login Google --}}
                        <div class="mt-3 d-grid gap-2">
                            <a href="{{ route('login.google') }}" class="btn btn-block btn-outline-secondary auth-form-btn">
                                <svg width="18" height="18" class="me-2" viewBox="0 0 48 48">
                                    <path fill="#EA4335" d="M24 9.5c3.5 0 6.5 1.2 8.9 3.2l6.6-6.6C35.4 2.5 30 0 24 0 14.6 0 6.5 5.5 2.6 13.5l7.7 6C12.1 13.2 17.6 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4 7.1-10 7.1-17z"/>
                                    <path fill="#FBBC05" d="M10.3 28.5C9.8 27 9.5 25.5 9.5 24s.3-3 .8-4.5l-7.7-6C.9 16.5 0 20.1 0 24s.9 7.5 2.6 10.5l7.7-6z"/>
                                    <path fill="#34A853" d="M24 48c6 0 11-2 14.7-5.3l-7.5-5.8c-2 1.4-4.6 2.1-7.2 2.1-6.4 0-11.9-3.7-14.3-9.5l-7.7 6C6.5 42.5 14.6 48 24 48z"/>
                                </svg>
                                Login dengan Google
                            </a>
                        </div>

                        <div class="my-2 d-flex align-items-center">
                            <div class="border flex-grow-1"></div>
                            <span class="mx-2 text-muted small">atau</span>
                            <div class="border flex-grow-1"></div>
                        </div>

                        {{-- Login biasa --}}
                        <form action="{{ route('login.post') }}" method="POST" class="pt-1">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="Email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-lg"
                                    placeholder="Password" required>
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    <i class="mdi mdi-login me-1"></i> LOGIN
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <span class="text-muted small">Belum punya akun?</span>
                                <a href="{{ route('register') }}" class="text-primary small fw-semibold ms-1">Daftar sebagai Vendor</a>
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
