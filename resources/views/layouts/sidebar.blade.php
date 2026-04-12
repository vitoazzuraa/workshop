<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

      <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <span class="menu-title">Dashboard</span>
          <i class="mdi mdi-home menu-icon"></i>
        </a>
      </li>

      <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kategori.index') }}">
          <span class="menu-title">Kategori</span>
          <i class="mdi mdi-format-list-bulleted menu-icon"></i>
        </a>
      </li>

      <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('buku.index') }}">
          <span class="menu-title">Buku</span>
          <i class="mdi mdi-book menu-icon"></i>
        </a>
      </li>


      <li class="nav-item">
        <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin: 10px 20px;">
      </li>

      <li class="nav-item {{ Request::is('wilayah*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('wilayah.index') }}">
          <span class="menu-title">Wilayah</span>
          <i class="mdi mdi-map-marker menu-icon"></i>
        </a>
      </li>

      <li class="nav-item {{ Request::is('barang*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('barang.index') }}">
          <span class="menu-title">Manajemen Barang</span>
          <i class="mdi mdi-cube-outline menu-icon"></i>
        </a>
      </li>

      <li class="nav-item {{ Request::is('kasir*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kasir.index') }}">
          <span class="menu-title">Kasir</span>
          <i class="mdi mdi-cart menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin: 10px 20px;">
      </li>

      <li class="nav-item {{ Request::is('menu*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.menu.index') }}">
          <span class="menu-title">Kelola Menu</span>
          <i class="mdi mdi-food menu-icon"></i>
        </a>
      </li>

      <li class="nav-item {{ Request::is('pesanan-masuk*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.pesanan.index') }}">
          <span class="menu-title">Pesanan Masuk</span>
          <i class="mdi mdi-cart-arrow-down menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin: 10px 20px;">
      </li>

      <li class="nav-item {{ Request::is('download-sertifikat') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pdf.sertifikat') }}">
          <span class="menu-title">Cetak Sertifikat</span>
          <i class="mdi mdi-certificate menu-icon"></i>
        </a>
      </li>

      <li class="nav-item {{ Request::is('download-undangan') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pdf.undangan') }}">
          <span class="menu-title">Cetak Undangan</span>
          <i class="mdi mdi-email-outline menu-icon"></i>
        </a>
      </li>

    </ul>
  </nav>
