@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
    </h3>
</div>

@if($role === 'vendor')

{{-- ── VENDOR DASHBOARD ─────────────────────────────────────────────── --}}
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Total Menu <i class="mdi mdi-food mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['menu'] }}</h2>
                <h6 class="card-text">Menu yang tersedia</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Pesanan Masuk <i class="mdi mdi-clock-outline mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['pesanan_masuk'] }}</h2>
                <h6 class="card-text">Menunggu pembayaran</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Pesanan Lunas <i class="mdi mdi-check-circle mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['pesanan_lunas'] }}</h2>
                <h6 class="card-text">Sudah dibayar</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Akses Cepat</h4>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-gradient-info btn-sm">
                        <i class="mdi mdi-food me-1"></i> Kelola Menu
                    </a>
                    <a href="{{ route('vendor.pesanan') }}" class="btn btn-gradient-success btn-sm">
                        <i class="mdi mdi-receipt me-1"></i> Pesanan Masuk
                    </a>
                    <a href="{{ route('vendor.scan') }}" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-qrcode-scan me-1"></i> Scan QR
                    </a>
                    <a href="{{ route('kantin.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="mdi mdi-store me-1"></i> Lihat Kantin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@else

{{-- ── ADMIN DASHBOARD ──────────────────────────────────────────────── --}}
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Total Kategori <i class="mdi mdi-tag-multiple mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['kategori'] }}</h2>
                <h6 class="card-text">Kategori buku tersedia</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Total Buku <i class="mdi mdi-book-open-page-variant mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['buku'] }}</h2>
                <h6 class="card-text">Koleksi buku terdaftar</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Total Barang <i class="mdi mdi-package-variant mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['barang'] }}</h2>
                <h6 class="card-text">Barang kantin terdaftar</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-primary card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Total Menu Kantin <i class="mdi mdi-food mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['menu'] }}</h2>
                <h6 class="card-text">Menu yang tersedia</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-warning card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle" />
                <h4 class="font-weight-normal mb-3">
                    Pesanan Lunas <i class="mdi mdi-check-circle mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-5">{{ $stats['pesanan_lunas'] }}</h2>
                <h6 class="card-text">Total transaksi selesai</h6>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Akses Cepat</h4>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('kategori.index') }}" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-tag-multiple me-1"></i> Kategori
                    </a>
                    <a href="{{ route('buku.index') }}" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-book-open-page-variant me-1"></i> Buku
                    </a>
                    <a href="{{ route('barang.index') }}" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-package-variant me-1"></i> Barang
                    </a>
                    <a href="{{ route('pos.ajax') }}" class="btn btn-gradient-info btn-sm">
                        <i class="mdi mdi-point-of-sale me-1"></i> Kasir
                    </a>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-gradient-success btn-sm">
                        <i class="mdi mdi-food me-1"></i> Menu Kantin
                    </a>
                    <a href="{{ route('kantin.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="mdi mdi-store me-1"></i> Lihat Kantin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@endsection
