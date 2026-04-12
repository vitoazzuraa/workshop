<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
    <style>
        body {
            overflow-x: hidden;
            background-color: #f4f7ff;
        }
        .navbar {
            left: 0 !important;
            width: 100% !important;
            transition: all 0.3s ease;
        }
        .main-panel {
            width: 100% !important;
            margin-left: 0 !important;
            padding-top: 70px;
        }
        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        .img-menu {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row shadow-sm">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ route('landing') }}">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <ul class="navbar-nav navbar-nav-right">
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="btn btn-gradient-success btn-sm">
                                <i class="mdi mdi-view-dashboard"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-gradient-primary btn-sm">
                                <i class="mdi mdi-login"></i> Login User
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
</body>
</html>
