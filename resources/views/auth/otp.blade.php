<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <style>
        .otp-input { font-size: 1.5rem; letter-spacing: 8px; text-align: center; font-weight: 700; }
    </style>
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
                        <h4>Verifikasi OTP</h4>
                        <h6 class="font-weight-light">Masukkan kode OTP yang dikirim ke email Anda.</h6>

                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                            </div>
                        @endif

                        <form action="{{ route('otp.verify') }}" method="POST" class="pt-3">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="otp" class="form-control form-control-lg otp-input"
                                    maxlength="6" placeholder="XXXXXX"
                                    autocomplete="off" required autofocus>
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    <i class="mdi mdi-check-circle me-1"></i> VERIFIKASI
                                </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                <a href="{{ route('login') }}" class="text-primary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali ke Login
                                </a>
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
<script>
    document.querySelector('[name="otp"]').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
</body>
</html>
