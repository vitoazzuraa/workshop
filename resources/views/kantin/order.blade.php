<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kantin — Pesan Makanan</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body { background: #f4f6f9; }
        .kantin-header { background: linear-gradient(135deg, #6f42c1, #a855f7); color: #fff; padding: 18px 0; margin-bottom: 30px; }
        .menu-card { cursor: pointer; transition: transform .15s, box-shadow .15s; }
        .menu-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(111,66,193,.2); }
        .menu-card.selected { border: 2px solid #6f42c1; background: #f8f4ff; }
    </style>
    {{-- Midtrans Snap.js — selalu dimuat agar window.snap tersedia --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>

{{-- Header --}}
<div class="kantin-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold"><i class="mdi mdi-store me-2"></i>Kantin Sistem</h4>
            <small class="opacity-75">Pesan makanan & minuman favorit Anda</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-light btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCekPesanan">
                <i class="mdi mdi-receipt me-1"></i> Cek Pesanan
            </button>
            <a href="{{ route('login') }}" class="btn btn-light btn-sm fw-semibold">
                <i class="mdi mdi-login me-1"></i> Admin / Vendor Login
            </a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        {{-- Panel menu --}}
        <div class="col-md-8">
            {{-- Tab vendor --}}
            <ul class="nav nav-tabs mb-3" id="vendorTab">
                @foreach($vendors as $i => $v)
                <li class="nav-item">
                    <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-vendor="{{ $v->idvendor }}">
                        <i class="mdi mdi-store-outline me-1"></i>{{ $v->nama_vendor }}
                    </button>
                </li>
                @endforeach
            </ul>

            <div id="menuContainer">
                @foreach($vendors as $i => $v)
                <div class="vendor-menu {{ $i === 0 ? '' : 'd-none' }}" data-vendor="{{ $v->idvendor }}">
                    @if($v->menu->isEmpty())
                        <p class="text-muted">Belum ada menu untuk vendor ini.</p>
                    @else
                    <div class="row g-3">
                        @foreach($v->menu as $m)
                        <div class="col-6 col-md-4">
                            <div class="card menu-card h-100"
                                data-id="{{ $m->idmenu }}"
                                data-nama="{{ $m->nama_menu }}"
                                data-harga="{{ $m->harga }}"
                                onclick="tambahMenu(this)">
                                @if($m->path_gambar)
                                    <img src="{{ asset('storage/' . $m->path_gambar) }}"
                                         alt="{{ $m->nama_menu }}"
                                         style="width:100%;height:100px;object-fit:cover;border-radius:4px 4px 0 0;">
                                @endif
                                <div class="card-body text-center py-2">
                                    @if(!$m->path_gambar)
                                        <i class="mdi mdi-food mdi-36px text-muted"></i>
                                    @endif
                                    <p class="fw-bold mb-1 mt-1 small">{{ $m->nama_menu }}</p>
                                    <p class="text-success fw-semibold mb-0 small">Rp {{ number_format($m->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Panel keranjang --}}
        <div class="col-md-4">
            <div class="card sticky-top" style="top:20px">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="mdi mdi-cart me-2"></i>Keranjang</h5>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Nama Pemesan <span class="text-danger">*</span></label>
                        <input type="text" id="namaPemesan" class="form-control form-control-sm" placeholder="Nama kamu...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">No. HP <span class="text-muted">(opsional)</span></label>
                        <input type="text" id="noHp" class="form-control form-control-sm" placeholder="08xx...">
                    </div>

                    <div id="keranjangList">
                        <p class="text-muted text-center py-3 small">Pilih menu di sebelah kiri</p>
                    </div>

                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total</span>
                        <span id="grandTotal" class="text-success">Rp 0</span>
                    </div>

                    <button class="btn btn-gradient-primary w-100" id="btnPesan" disabled>
                        <i class="mdi mdi-cash-register me-1"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cek Pesanan --}}
<div class="modal fade" id="modalCekPesanan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#6f42c1,#a855f7);color:#fff">
                <h5 class="modal-title"><i class="mdi mdi-receipt me-2"></i>Cek Pesanan Saya</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Masukkan <strong>nomor HP</strong> yang kamu pakai saat pesan, atau langsung masukkan <strong>ID Pesanan</strong> jika kamu ingat.</p>
                <div class="input-group mb-3">
                    <input type="text" id="inputCekPesanan" class="form-control"
                           placeholder="Nomor HP (08xx...) atau ID Pesanan">
                    <button class="btn btn-primary" id="btnCekPesanan">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
                <div id="hasilCekPesanan"></div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

let keranjang = {}; // idmenu => { nama, harga, jumlah, catatan }

// Tab vendor
document.querySelectorAll('#vendorTab .nav-link').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('#vendorTab .nav-link').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.vendor-menu').forEach(function (el) {
            el.classList.toggle('d-none', el.dataset.vendor != btn.dataset.vendor);
        });
    });
});

function tambahMenu(card) {
    const id    = card.dataset.id;
    const nama  = card.dataset.nama;
    const harga = parseInt(card.dataset.harga);
    if (keranjang[id]) {
        keranjang[id].jumlah++;
    } else {
        keranjang[id] = { nama, harga, jumlah: 1, catatan: '' };
    }
    card.classList.add('selected');
    renderKeranjang();
}

function ubahQty(id, delta) {
    if (!keranjang[id]) return;
    keranjang[id].jumlah += delta;
    if (keranjang[id].jumlah <= 0) {
        delete keranjang[id];
        document.querySelectorAll('.menu-card[data-id="' + id + '"]').forEach(c => c.classList.remove('selected'));
    }
    renderKeranjang();
}

function renderKeranjang() {
    const list      = document.getElementById('keranjangList');
    const keys      = Object.keys(keranjang);
    const btnPesan  = document.getElementById('btnPesan');

    if (keys.length === 0) {
        list.innerHTML = '<p class="text-muted text-center py-3 small">Pilih menu di sebelah kiri</p>';
        document.getElementById('grandTotal').textContent = 'Rp 0';
        btnPesan.disabled = true;
        return;
    }

    let html = '', total = 0;
    keys.forEach(function (id) {
        const item = keranjang[id];
        const sub  = item.harga * item.jumlah;
        total += sub;
        html += `<div class="border rounded p-2 mb-2 small">
            <div class="d-flex justify-content-between align-items-start">
                <span class="fw-semibold">${item.nama}</span>
                <span class="text-success">Rp ${sub.toLocaleString('id-ID')}</span>
            </div>
            <div class="d-flex align-items-center gap-2 mt-1">
                <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="ubahQty(${id}, -1)">−</button>
                <span class="fw-bold">${item.jumlah}</span>
                <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-0" onclick="ubahQty(${id}, 1)">+</button>
                <small class="text-muted ms-1">@ Rp ${item.harga.toLocaleString('id-ID')}</small>
            </div>
            <input type="text" class="form-control form-control-sm mt-1" placeholder="Catatan (opsional)..."
                   value="${item.catatan}"
                   oninput="keranjang[${id}].catatan = this.value">
        </div>`;
    });

    list.innerHTML = html;
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    btnPesan.disabled = false;
}

// Checkout
document.getElementById('btnPesan').addEventListener('click', async function () {
    const nama  = document.getElementById('namaPemesan').value.trim();
    const no_hp = document.getElementById('noHp').value.trim();
    if (!nama) {
        Swal.fire({ icon: 'warning', title: 'Nama kosong', text: 'Isi nama pemesan terlebih dulu.', confirmButtonColor: '#6f42c1' });
        return;
    }

    const items = Object.keys(keranjang).map(id => ({
        idmenu  : parseInt(id),
        jumlah  : keranjang[id].jumlah,
        catatan : keranjang[id].catatan || '',
    }));

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';

    try {
        const res = await axios.post('/kantin/pesan', { nama, no_hp, items });
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-cash-register me-1"></i> Bayar Sekarang';

        if (res.data.status !== 'success') {
            Swal.fire({ icon: 'error', title: 'Gagal', text: res.data.message, confirmButtonColor: '#6f42c1' });
            return;
        }

        const { idpesanan, snap_token } = res.data.data;

        // Buka popup pembayaran Midtrans Snap
        window.snap.pay(snap_token, {
            onSuccess: function () {
                window.location.href = '/kantin/status/' + idpesanan;
            },
            onPending: function () {
                window.location.href = '/kantin/status/' + idpesanan;
            },
            onError: function () {
                Swal.fire({ icon: 'error', title: 'Pembayaran Gagal', text: 'Silakan coba kembali.', confirmButtonColor: '#6f42c1' });
            },
            onClose: function () {
                Swal.fire({
                    icon: 'question',
                    title: 'Belum Selesai Bayar',
                    text: 'Pesanan #' + idpesanan + ' sudah tersimpan. Lanjutkan pembayaran?',
                    showCancelButton: true,
                    confirmButtonText: 'Bayar Sekarang',
                    cancelButtonText: 'Lihat Invoice',
                    confirmButtonColor: '#6f42c1',
                }).then(function (r) {
                    if (r.isConfirmed) {
                        window.snap.pay(snap_token, this);
                    } else {
                        window.location.href = '/kantin/status/' + idpesanan;
                    }
                });
            },
        });
    } catch (e) {
        btn.disabled = false;
        btn.innerHTML = '<i class="mdi mdi-cash-register me-1"></i> Bayar Sekarang';
        const msg = e.response?.data?.message || e.message || 'Unknown error';
        Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#6f42c1' });
    }
});

// ── Cek Pesanan ──────────────────────────────────────────────────────────
async function doCekPesanan() {
    const q = document.getElementById('inputCekPesanan').value.trim();
    if (!q) return;
    const hasilEl = document.getElementById('hasilCekPesanan');
    hasilEl.innerHTML = '<p class="text-muted small">Mencari...</p>';
    try {
        const res = await axios.get('/kantin/cek-pesanan', { params: { q } });
        if (res.data.status === 'success' && res.data.redirect) {
            window.location.href = res.data.redirect;
            return;
        }
        if (res.data.status === 'success' && res.data.data) {
            const list = res.data.data;
            let html = '<div class="list-group">';
            list.forEach(function (p) {
                const badgeClass = p.status_bayar ? 'success' : 'warning';
                html += `<a href="${p.url}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>#${p.id}</strong> — ${p.nama}
                            <br><small class="text-muted">${p.waktu} · ${p.total}</small>
                        </div>
                        <span class="badge bg-${badgeClass}">${p.status}</span>
                    </div>
                </a>`;
            });
            html += '</div>';
            hasilEl.innerHTML = html;
        } else {
            hasilEl.innerHTML = '<p class="text-danger small">' + (res.data.message || 'Tidak ditemukan.') + '</p>';
        }
    } catch (e) {
        hasilEl.innerHTML = '<p class="text-danger small">Gagal menghubungi server.</p>';
    }
}

document.getElementById('btnCekPesanan').addEventListener('click', doCekPesanan);
document.getElementById('inputCekPesanan').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') doCekPesanan();
});
document.getElementById('modalCekPesanan').addEventListener('shown.bs.modal', function () {
    document.getElementById('inputCekPesanan').focus();
    document.getElementById('hasilCekPesanan').innerHTML = '';
});
</script>
</body>
</html>
