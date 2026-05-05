@extends('layouts.app')
@section('title', 'Demo Tabel jQuery')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-language-javascript"></i>
        </span> Demo JS / jQuery — Tabel Biasa
    </h3>
</div>

<div class="row">
    {{-- Card demo manipulasi DOM --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manipulasi DOM dengan jQuery</h4>
                <p class="card-description">Tambah / hapus / ubah baris tabel secara dinamis.</p>

                <div class="input-group mb-3">
                    <input type="text" id="inputNama" class="form-control" placeholder="Nama barang...">
                    <input type="number" id="inputHarga" class="form-control" placeholder="Harga...">
                    <button class="btn btn-gradient-primary" id="btnTambahRow">
                        <i class="mdi mdi-plus"></i> Tambah
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelDemo">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Nasi Goreng</td>
                                <td>Rp 15.000</td>
                                <td><button class="btn btn-sm btn-gradient-danger btn-hapus-row"><i class="mdi mdi-delete"></i></button></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Es Teh</td>
                                <td>Rp 5.000</td>
                                <td><button class="btn btn-sm btn-gradient-danger btn-hapus-row"><i class="mdi mdi-delete"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-2">Jumlah baris: <strong id="jumlahBaris">2</strong></p>
            </div>
        </div>
    </div>

    {{-- Card demo event & animasi --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Event & Efek jQuery</h4>
                <p class="card-description">Demonstrasi event handler, hide/show, dan efek.</p>

                <div class="mb-3">
                    <button class="btn btn-gradient-info me-2" id="btnToggle">Toggle Panel</button>
                    <button class="btn btn-gradient-warning me-2" id="btnHighlight">Highlight Baris</button>
                    <button class="btn btn-outline-secondary" id="btnReset">Reset</button>
                </div>

                <div id="panelToggle" class="alert alert-info">
                    Panel ini bisa di-toggle dengan tombol di atas!
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-hover" id="tabelEvent">
                        <thead>
                            <tr><th>#</th><th>Item</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>Item Alpha</td><td><span class="badge badge-gradient-success">Aktif</span></td></tr>
                            <tr><td>2</td><td>Item Beta</td><td><span class="badge badge-gradient-warning">Pending</span></td></tr>
                            <tr><td>3</td><td>Item Gamma</td><td><span class="badge badge-gradient-danger">Nonaktif</span></td></tr>
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Klik baris untuk pilih/batal pilih</small>
            </div>
        </div>
    </div>

    {{-- Card demo spinner button --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Spinner Button Pattern</h4>
                <p class="card-description">Pattern reusable untuk semua tombol submit.</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-gradient-primary" id="btnDemo1" onclick="demoSpinner(this, 1500)">
                        <i class="mdi mdi-send me-1"></i> Kirim Data
                    </button>
                    <button class="btn btn-gradient-success" id="btnDemo2" onclick="demoSpinner(this, 2000)">
                        <i class="mdi mdi-content-save me-1"></i> Simpan
                    </button>
                    <button class="btn btn-gradient-danger" id="btnDemo3" onclick="demoSpinner(this, 1000)">
                        <i class="mdi mdi-delete me-1"></i> Hapus
                    </button>
                </div>

                <div class="alert alert-secondary" id="spinnerLog">Log aksi akan muncul di sini...</div>
            </div>
        </div>
    </div>

    {{-- Card demo live search --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Live Search jQuery</h4>
                <p class="card-description">Filter tabel secara real-time saat mengetik.</p>

                <input type="text" id="liveSearch" class="form-control mb-3" placeholder="Ketik untuk mencari...">

                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelSearch">
                        <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th></tr></thead>
                        <tbody>
                            <tr><td>Nasi Goreng</td><td>Makanan</td><td>Rp 15.000</td></tr>
                            <tr><td>Mie Ayam</td><td>Makanan</td><td>Rp 12.000</td></tr>
                            <tr><td>Es Teh</td><td>Minuman</td><td>Rp 5.000</td></tr>
                            <tr><td>Jus Alpukat</td><td>Minuman</td><td>Rp 10.000</td></tr>
                            <tr><td>Roti Bakar</td><td>Snack</td><td>Rp 8.000</td></tr>
                            <tr><td>Pisang Goreng</td><td>Snack</td><td>Rp 6.000</td></tr>
                        </tbody>
                    </table>
                </div>
                <small id="searchInfo" class="text-muted"></small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
$(function () {
    // === Tambah baris ===
    $('#btnTambahRow').on('click', function () {
        const nama  = $('#inputNama').val().trim();
        const harga = parseInt($('#inputHarga').val()) || 0;
        if (!nama) { alert('Nama wajib diisi!'); return; }

        const no   = $('#tabelDemo tbody tr').length + 1;
        const html = `<tr>
            <td>${no}</td>
            <td>${nama}</td>
            <td>Rp ${harga.toLocaleString('id-ID')}</td>
            <td><button class="btn btn-sm btn-gradient-danger btn-hapus-row"><i class="mdi mdi-delete"></i></button></td>
        </tr>`;
        $('#tabelDemo tbody').append(html);
        $('#inputNama').val('').focus();
        $('#inputHarga').val('');
        updateJumlah();
    });

    // Hapus baris (delegated)
    $('#tabelDemo').on('click', '.btn-hapus-row', function () {
        $(this).closest('tr').remove();
        updateJumlah();
    });

    function updateJumlah() {
        const n = $('#tabelDemo tbody tr').length;
        $('#jumlahBaris').text(n);
        // renomor
        $('#tabelDemo tbody tr').each(function (i) { $(this).find('td:first').text(i + 1); });
    }

    // === Toggle panel ===
    $('#btnToggle').on('click', function () {
        $('#panelToggle').slideToggle(300);
    });

    // === Highlight baris ===
    $('#btnHighlight').on('click', function () {
        $('#tabelEvent tbody tr').addClass('table-warning');
    });
    $('#btnReset').on('click', function () {
        $('#tabelEvent tbody tr').removeClass('table-warning table-primary');
    });

    // Klik baris pilih/batal
    $('#tabelEvent tbody').on('click', 'tr', function () {
        $(this).toggleClass('table-primary');
    });

    // === Live search ===
    $('#liveSearch').on('keyup', function () {
        const q = $(this).val().toLowerCase();
        let found = 0;
        $('#tabelSearch tbody tr').each(function () {
            const match = $(this).text().toLowerCase().includes(q);
            $(this).toggle(match);
            if (match) found++;
        });
        $('#searchInfo').text(q ? `Ditemukan ${found} hasil untuk "${q}"` : '');
    });
});

// Spinner demo: simulasi proses async
function demoSpinner(btn, delay) {
    const restore = btnLoading(btn);
    const log = document.getElementById('spinnerLog');
    log.textContent = `Memproses "${btn.dataset.label || btn.textContent.trim()}"...`;
    setTimeout(function () {
        restore();
        log.textContent = `Selesai pada ${new Date().toLocaleTimeString('id-ID')}`;
    }, delay);
}
</script>
@endsection
