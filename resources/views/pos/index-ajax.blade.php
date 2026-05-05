@extends('layouts.app')
@section('title', 'POS Kasir — jQuery Ajax')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-point-of-sale"></i>
        </span> POS Kasir — jQuery Ajax
    </h3>
</div>

<div class="row">
    {{-- Form cari barang --}}
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

    {{-- Keranjang --}}
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

{{-- Modal struk --}}
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

// ====== Cari barang via jQuery $.ajax ======
$('#btnCari').on('click', cariBarang);
$('#inputKode').on('keypress', function (e) {
    if (e.which === 13) cariBarang();
});

function cariBarang() {
    const kode = $('#inputKode').val().trim();
    if (!kode) return;

    $.ajax({
        url: '/pos/cari',
        type: 'GET',
        data: { kode: kode },
        beforeSend: function () {
            $('#infoBarang').removeClass('d-none alert-success alert-danger')
                .addClass('alert-secondary').text('Mencari...');
        },
        success: function (res) {
            if (res.status === 'success') {
                barangAktif = res.data;
                $('#infoBarang').removeClass('alert-secondary alert-danger').addClass('alert-success')
                    .html(`<strong>${res.data.nama}</strong><br>Harga: <strong>Rp ${parseInt(res.data.harga).toLocaleString('id-ID')}</strong>`);
                $('#btnTambahKeranjang').prop('disabled', false);
            } else {
                barangAktif = null;
                $('#infoBarang').removeClass('alert-secondary alert-success').addClass('alert-danger')
                    .text(res.message);
                $('#btnTambahKeranjang').prop('disabled', true);
            }
        },
        error: function () {
            $('#infoBarang').removeClass('alert-secondary alert-success').addClass('alert-danger')
                .text('Terjadi kesalahan koneksi.');
        }
    });
}

// ====== Tambah ke keranjang ======
$('#btnTambahKeranjang').on('click', function () {
    if (!barangAktif) return;
    const jumlah = parseInt($('#inputJumlah').val()) || 1;
    const existing = keranjang.find(i => i.id_barang === barangAktif.id_barang);
    if (existing) {
        existing.jumlah += jumlah;
        existing.subtotal = existing.jumlah * existing.harga;
    } else {
        keranjang.push({
            id_barang: barangAktif.id_barang,
            nama: barangAktif.nama,
            harga: parseInt(barangAktif.harga),
            jumlah: jumlah,
            subtotal: jumlah * parseInt(barangAktif.harga),
        });
    }
    renderKeranjang();
    $('#inputKode').val('').focus();
    $('#inputJumlah').val(1);
    barangAktif = null;
    $('#infoBarang').addClass('d-none');
    $('#btnTambahKeranjang').prop('disabled', true);
});

function renderKeranjang() {
    const $body = $('#keranjangBody');
    if (keranjang.length === 0) {
        $body.html('<tr id="emptyRow"><td colspan="6" class="text-center text-muted py-4"><i class="mdi mdi-cart-outline mdi-48px d-block"></i>Keranjang kosong</td></tr>');
        $('#totalItem').text(0);
        $('#totalHarga').text('Rp 0');
        $('#btnBayar').prop('disabled', true);
        return;
    }
    let html = '';
    let total = 0;
    keranjang.forEach(function (item, idx) {
        total += item.subtotal;
        html += `<tr>
            <td><span class="badge badge-gradient-primary">${item.id_barang}</span></td>
            <td>${item.nama}</td>
            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
            <td>
                <div class="input-group input-group-sm" style="width:90px">
                    <button class="btn btn-outline-secondary btn-qty-min" data-idx="${idx}">-</button>
                    <input type="number" class="form-control text-center qty-input" value="${item.jumlah}" data-idx="${idx}" min="1" style="width:40px">
                    <button class="btn btn-outline-secondary btn-qty-plus" data-idx="${idx}">+</button>
                </div>
            </td>
            <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
            <td><button class="btn btn-sm btn-gradient-danger btn-hapus" data-idx="${idx}"><i class="mdi mdi-delete"></i></button></td>
        </tr>`;
    });
    $body.html(html);
    const totalItem = keranjang.reduce((s, i) => s + i.jumlah, 0);
    $('#totalItem').text(totalItem);
    $('#totalHarga').text('Rp ' + total.toLocaleString('id-ID'));
    $('#btnBayar').prop('disabled', false);
}

// Event delegasi untuk qty dan hapus
$('#tblKeranjang').on('click', '.btn-hapus', function () {
    keranjang.splice($(this).data('idx'), 1);
    renderKeranjang();
});
$('#tblKeranjang').on('click', '.btn-qty-min', function () {
    const idx = $(this).data('idx');
    if (keranjang[idx].jumlah > 1) { keranjang[idx].jumlah--; keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga; renderKeranjang(); }
});
$('#tblKeranjang').on('click', '.btn-qty-plus', function () {
    const idx = $(this).data('idx');
    keranjang[idx].jumlah++; keranjang[idx].subtotal = keranjang[idx].jumlah * keranjang[idx].harga; renderKeranjang();
});
$('#tblKeranjang').on('change', '.qty-input', function () {
    const idx = $(this).data('idx');
    const v = parseInt($(this).val()) || 1;
    keranjang[idx].jumlah = v; keranjang[idx].subtotal = v * keranjang[idx].harga; renderKeranjang();
});

// ====== Bayar via $.ajax ======
$('#btnBayar').on('click', function () {
    const restore = btnLoading(this);
    $.ajax({
        url: '/pos/bayar',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ items: keranjang, _token: $('meta[name="csrf-token"]').attr('content') }),
        success: function (res) {
            restore();
            if (res.status === 'success') {
                const total = keranjang.reduce((s, i) => s + i.subtotal, 0);
                $('#idPenjualan').text(res.data.id_penjualan);
                $('#totalStruk').text('Total: Rp ' + total.toLocaleString('id-ID'));
                $('#modalStruk').modal('show');
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        },
        error: function () { restore(); Swal.fire('Error', 'Koneksi bermasalah.', 'error'); }
    });
});

$('#btnSelesai').on('click', function () {
    keranjang = [];
    renderKeranjang();
});
</script>
@endsection
