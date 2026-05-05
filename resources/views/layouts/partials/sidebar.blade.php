<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        {{-- Profile --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ session('user.name') }}</span>
                    <span class="text-secondary text-small text-capitalize">{{ session('user.role') }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        {{-- Dashboard --}}
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        @if(session('user.role') === 'admin')

        {{-- Koleksi Buku --}}
        <li class="nav-item {{ request()->routeIs('kategori.*', 'buku.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-buku" aria-expanded="{{ request()->routeIs('kategori.*', 'buku.*') ? 'true' : 'false' }}">
                <span class="menu-title">Koleksi Buku</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('kategori.*', 'buku.*') ? 'show' : '' }}" id="menu-buku">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                           href="{{ route('kategori.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}"
                           href="{{ route('buku.index') }}">Buku</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Barang --}}
        <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Data Barang</span>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
        </li>

        {{-- Kasir / POS --}}
        <li class="nav-item {{ request()->routeIs('pos.*', 'penjualan.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-pos" aria-expanded="{{ request()->routeIs('pos.*', 'penjualan.*') ? 'true' : 'false' }}">
                <span class="menu-title">Kasir</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-point-of-sale menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('pos.*', 'penjualan.*') ? 'show' : '' }}" id="menu-pos">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pos.ajax') ? 'active' : '' }}"
                           href="{{ route('pos.ajax') }}">POS (Ajax)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pos.axios') ? 'active' : '' }}"
                           href="{{ route('pos.axios') }}">POS (Axios)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}"
                           href="{{ route('penjualan.index') }}">Riwayat</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Demo JS --}}
        <li class="nav-item {{ request()->routeIs('js.*', 'wilayah.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-demo" aria-expanded="{{ request()->routeIs('js.*', 'wilayah.*') ? 'true' : 'false' }}">
                <span class="menu-title">Demo</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-code-braces menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('js.*', 'wilayah.*') ? 'show' : '' }}" id="menu-demo">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('js.*') ? 'active' : '' }}"
                           href="{{ route('js.table') }}">JS / jQuery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('wilayah.*') ? 'active' : '' }}"
                           href="{{ route('wilayah.index') }}">Wilayah AJAX</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Dokumen PDF --}}
        <li class="nav-item {{ request()->routeIs('pdf.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-pdf" aria-expanded="{{ request()->routeIs('pdf.*') ? 'true' : 'false' }}">
                <span class="menu-title">Dokumen PDF</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('pdf.*') ? 'show' : '' }}" id="menu-pdf">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">Sertifikat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">Undangan</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Customer --}}
        <li class="nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('customer.index') }}">
                <span class="menu-title">Data Customer</span>
                <i class="mdi mdi-account-group menu-icon"></i>
            </a>
        </li>

        {{-- Guest Kantin --}}
        <li class="nav-item {{ request()->routeIs('guest.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('guest.index') }}">
                <span class="menu-title">Data Guest</span>
                <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
        </li>

        {{-- Scanner --}}
        <li class="nav-item {{ request()->routeIs('barcode.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barcode.scan') }}">
                <span class="menu-title">Barcode Scanner</span>
                <i class="mdi mdi-barcode-scan menu-icon"></i>
            </a>
        </li>

        @endif {{-- end admin --}}

        {{-- Kantin (admin + vendor) --}}
        <li class="nav-item {{ request()->routeIs('vendor.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-kantin" aria-expanded="{{ request()->routeIs('vendor.*') ? 'true' : 'false' }}">
                <span class="menu-title">Kantin</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-store menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('vendor.*') ? 'show' : '' }}" id="menu-kantin">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.menu.*') ? 'active' : '' }}"
                           href="{{ route('vendor.menu.index') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.pesanan') ? 'active' : '' }}"
                           href="{{ route('vendor.pesanan') }}">Pesanan Lunas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.scan') ? 'active' : '' }}"
                           href="{{ route('vendor.scan') }}">Scan QR</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
