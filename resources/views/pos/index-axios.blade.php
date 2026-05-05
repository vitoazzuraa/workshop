@extends('layouts.app')
@section('title', 'POS Kasir — Axios')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-point-of-sale"></i>
        </span> POS Kasir — Axios
    </h3>
</div>

<div class="row">
    <div class="col-md-5 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cari Barang</h4>
                <div class="input-group mb-3">
                    <input type="text" id="inputKode" class="form-control"
                        placeholder="Scan / ketik ID Barang..." autofocus>
                    <button class="btn btn-gradient-primary" id="btnCari">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
                <div id="infoBarang" class="alert alert-secondary d-none"></div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah</label>
                    <input type="number" id="inputJumlah" class="form-control" value="1" min="1">
                </div>
                <button class="btn btn-gradient-success w-100" id="btnTambahKeranjang" disabled>
                    <i class="mdi mdi-cart-plus me-1"></i> Tambah ke Keranjang
                </button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h4 class="card-title">Ringkasan</h4>
                <table class="table table-sm mb-0">
                    <tr><td>Total Item</td><td class="text-end fw-bold" id="totalItem">0</td></tr>
                    <tr><td>Total Harga</td><td class="text-end fw-bold text-success" id="totalHarga">Rp 0</td></tr>
                </table>
                <hr>
                <button class="btn btn-gradient-primary w-100" id="btnBayar" disabled>
                    <i class="mdi mdi-cash me-1"></i> Proses Bayar
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblKeranjang">
                        <thead>
                            <tr>
                                <th>ID</th><th>Nama</th><th>Harga</th>
                                <th>Qty</th><th>Subtotal</th><th></th>
                            </tr>
                        </thead>
                        <tbody id="keranjangBody">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="mdi mdi-cart-outline mdi-48px d-block"></i>
                                    Keranjang kosong
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title"><i class="mdi mdi-check-circle me-2"></i>Transaksi Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="mdi mdi-cash-check mdi-48px text-success"></i>
                <p class="mt-2">ID Penjualan: <strong id="idPenjualan"></strong></p>
                <p id="totalStruk" class="fs-5 fw-bold text-success"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-primary" data-bs-dismiss="modal" id="btnSelesai">
                    <i class="mdi mdi-refresh me-1"></i> Transaksi Baru
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
let keranjang = [];
let barangAktif = null;

// ====== Cari barang via Axios ======
document.getElementById('btnCari').addEventListener('click', cariBarang);
document.getElementById('inputKode').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') cariBarang();
});

async function cariBarang() {
    const kode = document.getElementById('inputKode').value.trim();
    if (!kode) return;
    const info = document.getElementById('infoBarang');
    info.className = 'alert alert-secondary';
    info.textContent = 'Mencari...';

    try {
        const res = await axios.get('/pos/cari', { params: { kode } });
        if (res.data.status === 'success') {
            barangAktif = res.data.data;
            info.className = 'alert alert-success';
            info.innerHTML = `<strong>${barangAktif.nama}</strong><br>Harga: <strong>Rp ${parseInt(barangAktif.harga).toLocaleString('id-ID')}</strong>`;
            document.getElementById('btnTambahKeranjang').disabled = false;
        } else {
            barangAktif = null;
            info.className = 'alert alert-danger';
            info.textContent = res.data.message;
            document.getElementById('btnTambahKeranjang').disabled = true;
        }
    } catch (e) {
        info.className = 'alert alert-danger';
        info.textContent = 'Kesalahan koneksi.';
    }
}

document.getElementById('btnTambahKeranjang').addEventListener('click', function () {
    if (!barangAktif) return;
    const jumlah = parseInt(document.getElementById('inputJumlah').value) || 1;
    const existing = keranjang.find(i => i.id_barang === barangAktif.id_barang);
    if (existing) {
        existing.jumlah += jumlah;
        existing.subtotal = existing.jumlah * existing.harga;
    } else {
        keranjang.push({ id_barang: barangAktif.id_barang, nama: barangAktif.nama, harga: parseInt(barangAktif.harga), jumlah, subtotal: jumlah * parseInt(barangAktif.harga) });
    }
    renderKeranjang();
    document.getElementById('inputKode').value = '';
    document.getElementById('inputJumlah').value = 1;
    document.getElementById('infoBarang').className = 'alert alert-secondary d-none';
    document.getElementById('btnTambahKeranjang').disabled = true;
    barangAktif = null;
    document.getElementById('inputKode').focus();
});

function renderKeranjang() {
    const body = document.getElementById('keranjangBody');
    if (keranjang.length === 0) {
        body.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="mdi mdi-cart-outline mdi-48px d-block"></i>Keranjang kosong</td></tr>';
        document.getElementById('totalItem').textContent = 0;
        document.getElementById('totalHarga').textContent = 'Rp 0';
        document.getElementById('btnBayar').disabled = true;
        return;
    }
    let html = '';
    let total = 0;
    keranjang.forEach((item, idx) => {
        total += item.subtotal;
        html += `<tr>
            <td><span class="badge badge-gradient-primary">${item.id_barang}</span></td>
            <td>${item.nama}</td>
            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
            <td>
                <div class="input-group input-group-sm" style="width:90px">
                    <button class="btn btn-outline-secondary" onclick="ubahQty(${idx},-1)">-</button>
                    <input type="number" class="form-control text-center" value="${item.jumlah}" min="1" onchange="setQty(${idx},this.value)" style="width:40px">
                    <button class="btn btn-outline-secondary" onclick="ubahQty(${idx},1)">+</button>
                </div>
            </td>
            <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
            <td><button class="btn btn-sm btn-gradient-danger" onclick="hapusItem(${idx})"><i class="mdi mdi-delete"></i></button></td>
        </tr>`;
    });
    body.innerHTML = html;
    document.getElementById('totalItem').textContent = keranjang.reduce((s, i) => s + i.jumlah, 0);
    document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('btnBayar').disabled = false;
}

function ubahQty(idx, delta) {
    keranjang[idx].jumlah = Math.max(1, keranjang[idx].jumlah + delta);
    keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga;
    renderKeranjang();
}
function setQty(idx, val) {
    keranjang[idx].jumlah = Math.max(1, parseInt(val) || 1);
    keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga;
    renderKeranjang();
}
function hapusItem(idx) { keranjang.splice(idx, 1); renderKeranjang(); }

// ====== Bayar via Axios ======
document.getElementById('btnBayar').addEventListener('click', async function () {
    const restore = btnLoading(this);
    try {
        const res = await axios.post('/pos/bayar', { items: keranjang });
        restore();
        if (res.data.status === 'success') {
            const total = keranjang.reduce((s, i) => s + i.subtotal, 0);
            document.getElementById('idPenjualan').textContent = res.data.data.id_penjualan;
            document.getElementById('totalStruk').textContent = 'Total: Rp ' + total.toLocaleString('id-ID');
            new bootstrap.Modal(document.getElementById('modalStruk')).show();
        } else {
            Swal.fire('Gagal', res.data.message, 'error');
        }
    } catch (e) {
        restore();
        Swal.fire('Error', 'Koneksi bermasalah.', 'error');
    }
});

document.getElementById('btnSelesai').addEventListener('click', function () {
    keranjang = [];
    renderKeranjang();
});
</script>
@endsection
