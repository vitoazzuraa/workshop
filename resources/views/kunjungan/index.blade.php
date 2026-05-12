@extends('layouts.app')
@section('title', 'Kunjungan Toko')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-radius"></i>
        </span> Kunjungan Toko
    </h3>
    <button class="btn btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="mdi mdi-plus me-1"></i> Tambah Toko
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Lokasi Toko</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblToko">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Toko</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Akurasi (m)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tokos as $i => $t)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <img src="{{ route('kunjungan.barcode', $t->barcode) }}"
                                         alt="{{ $t->barcode }}"
                                         style="height: 30px;">
                                    <br>
                                    <small class="text-muted">{{ $t->barcode }}</small>
                                </td>
                                <td class="fw-semibold">{{ $t->nama_toko }}</td>
                                <td>{{ $t->latitude }}</td>
                                <td>{{ $t->longitude }}</td>
                                <td>{{ $t->accuracy }}</td>
                                <td>
                                    <a href="{{ route('kunjungan.barcode', $t->barcode) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-gradient-info"
                                       title="Cetak Barcode">
                                        <i class="mdi mdi-printer"></i>
                                    </a>
                                    <button class="btn btn-sm btn-gradient-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapus"
                                        data-barcode="{{ $t->barcode }}"
                                        data-nama="{{ $t->nama_toko }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-5 grid-margin">

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Scan Barcode Toko</h4>
                <p class="card-description">Arahkan kamera ke barcode pada label toko, atau ketik kode secara manual.</p>

                <div id="reader" style="width: 100%;"></div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-gradient-primary" id="btnMulaiScan">
                        <i class="mdi mdi-camera me-1"></i> Mulai Scan
                    </button>
                    <button class="btn btn-outline-secondary d-none" id="btnStopScan">
                        <i class="mdi mdi-stop me-1"></i> Stop
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Input Kode Manual</h6>
                <p class="card-description">Gunakan fitur ini jika kamera tidak tersedia.</p>
                <div class="input-group">
                    <input type="text" id="inputKodeManual" class="form-control" placeholder="Masukkan kode toko...">
                    <button class="btn btn-gradient-info" id="btnCariManual">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Hasil Verifikasi</h4>

                <div id="statusAwal" class="alert alert-secondary">
                    Scan barcode toko terlebih dahulu untuk memulai verifikasi kunjungan.
                </div>

                <div id="dataToko" class="d-none">

                    <h6 class="fw-semibold mb-2">Data Toko</h6>
                    <table class="table table-sm mb-3">
                        <tr>
                            <td class="text-muted" style="width: 35%">Kode Barcode</td>
                            <td class="fw-semibold" id="tokoBarcode"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Toko</td>
                            <td class="fw-semibold" id="tokoNama"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Latitude</td>
                            <td id="tokoLat"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Longitude</td>
                            <td id="tokoLng"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Akurasi Toko</td>
                            <td id="tokoAkurasi"></td>
                        </tr>
                    </table>

                    <button class="btn btn-gradient-success w-100" id="btnAmbilLokasi">
                        <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi Saya
                    </button>

                    <div id="statusMencariGps" class="alert alert-info mt-2 d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Mencari sinyal GPS yang akurat, harap tunggu...
                    </div>
                </div>

                <div id="hasilVerifikasi" class="d-none">

                    <div id="kotakHasil" class="alert text-center mb-3">
                        <strong id="teksHasil" style="font-size: 1.2rem;"></strong>
                        <div id="keteranganHasil" class="small mt-1"></div>
                    </div>

                    <h6 class="fw-semibold mb-2">Rincian Perhitungan</h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted" style="width: 45%">Latitude Sales</td>
                            <td id="salesLat"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Longitude Sales</td>
                            <td id="salesLng"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Akurasi GPS Sales</td>
                            <td id="salesAkurasi"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jarak Aktual</td>
                            <td class="fw-semibold" id="jarakAktual"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Threshold Efektif</td>
                            <td id="thresholdEfektif"></td>
                        </tr>
                    </table>

                    <button class="btn btn-outline-secondary w-100 mt-2" id="btnVerifikasiLain">
                        <i class="mdi mdi-refresh me-1"></i> Verifikasi Toko Lain
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('kunjungan.store') }}" method="POST" id="formTambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-map-marker-plus me-2"></i>Tambah Toko
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" name="nama_toko" class="form-control" required maxlength="50">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                        <input type="text" name="latitude" id="inputLat" class="form-control" required placeholder="Contoh: -7.250445">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                        <input type="text" name="longitude" id="inputLng" class="form-control" required placeholder="Contoh: 112.768845">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Akurasi GPS (meter) <span class="text-danger">*</span></label>
                        <input type="text" name="accuracy" id="inputAkurasi" class="form-control" required value="0">
                        <small class="text-muted">Nilai akurasi GPS saat titik lokasi diambil.</small>
                    </div>

                    <div id="statusGeoloc" class="alert d-none"></div>

                    <button type="button" class="btn btn-outline-info w-100" id="btnGeoloc">
                        <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Koordinat Otomatis
                    </button>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnSimpanToko" class="btn btn-gradient-primary"
                        onclick="submitWithSpinner('formTambah', 'btnSimpanToko')">
                        <i class="mdi mdi-content-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <form id="formHapus" method="POST">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-alert me-2"></i>Hapus Toko
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus toko <strong id="hapusNamaToko"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnHapusToko" class="btn btn-gradient-danger"
                        onclick="submitWithSpinner('formHapus', 'btnHapusToko')">
                        <i class="mdi mdi-delete me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection


@section('js-page')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>

var tokoSaatIni = null;

$(function () {
    $('#tblToko').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
        columnDefs: [
            { orderable: false, targets: [1, 6] }
        ]
    });

    $('#modalHapus').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        $('#formHapus').attr('action', '/kunjungan-toko/' + btn.dataset.barcode);
        $('#hapusNamaToko').text(btn.dataset.nama);
    });
});

var audioBeep = (function () {
    var a = new Audio('{{ asset("assets/audio/beep.mp3") }}');
    a.volume = 0.6;
    return a;
})();

function bunyikanBeep() {
    audioBeep.play().catch(function () {
        try {
            var ctx  = new (window.AudioContext || window.webkitAudioContext)();
            var osc  = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 1200;
            gain.gain.setValueAtTime(0.5, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.2);
            osc.start();
            osc.stop(ctx.currentTime + 0.2);
        } catch (e) {}
    });
}

var scanner    = null;
var sedangScan = false;

$('#btnMulaiScan').on('click', function () {
    if (sedangScan) return;

    scanner = new Html5Qrcode('reader');
    scanner.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 250, height: 100 } },
        function (kodeHasil) {
            bunyikanBeep();
            scanner.stop().then(function () {
                sedangScan = false;
                $('#btnMulaiScan').removeClass('d-none');
                $('#btnStopScan').addClass('d-none');
            });
            tampilkanDataToko(kodeHasil);
        },
        function () {}
    ).then(function () {
        sedangScan = true;
        $('#btnMulaiScan').addClass('d-none');
        $('#btnStopScan').removeClass('d-none');
    }).catch(function (pesan) {
        alert('Kamera tidak dapat diakses: ' + pesan);
    });
});

$('#btnStopScan').on('click', function () {
    if (scanner && sedangScan) {
        scanner.stop().then(function () {
            sedangScan = false;
            $('#btnMulaiScan').removeClass('d-none');
            $('#btnStopScan').addClass('d-none');
        });
    }
});

$('#btnCariManual').on('click', function () {
    var kode = $('#inputKodeManual').val().trim();
    if (kode) {
        bunyikanBeep();
        tampilkanDataToko(kode);
    }
});

$('#inputKodeManual').on('keypress', function (e) {
    if (e.key === 'Enter') {
        var kode = $(this).val().trim();
        if (kode) {
            bunyikanBeep();
            tampilkanDataToko(kode);
        }
    }
});

function tampilkanDataToko(barcode) {
    $('#statusAwal').text('Mencari data toko...').removeClass('d-none alert-secondary alert-danger').addClass('alert-secondary');
    $('#dataToko').addClass('d-none');
    $('#hasilVerifikasi').addClass('d-none');

    $.ajax({
        url: '/kunjungan-toko/scan/' + encodeURIComponent(barcode),
        method: 'GET',
        success: function (res) {
            if (res.status !== 'success') {
                $('#statusAwal').text(res.message).removeClass('alert-secondary').addClass('alert-danger');
                return;
            }

            tokoSaatIni = res.data;

            $('#tokoBarcode').text(res.data.barcode);
            $('#tokoNama').text(res.data.nama_toko);
            $('#tokoLat').text(res.data.latitude);
            $('#tokoLng').text(res.data.longitude);
            $('#tokoAkurasi').text(res.data.accuracy + ' meter');

            $('#statusAwal').addClass('d-none');
            $('#dataToko').removeClass('d-none');
        },
        error: function () {
            $('#statusAwal').text('Gagal menghubungi server. Periksa koneksi internet Anda.').removeClass('alert-secondary').addClass('alert-danger');
        }
    });
}

function getAccuratePosition(targetAkurasi, maxTunggu) {
    return new Promise(function (resolve, reject) {
        var hasilTerbaik = null;
        var waktuMulai   = Date.now();

        var watchId = navigator.geolocation.watchPosition(
            function (posisi) {
                var akurasi = posisi.coords.accuracy;

                if (!hasilTerbaik || akurasi < hasilTerbaik.coords.accuracy) {
                    hasilTerbaik = posisi;
                }

                if (akurasi <= targetAkurasi) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(hasilTerbaik);
                }

                if (Date.now() - waktuMulai >= maxTunggu) {
                    navigator.geolocation.clearWatch(watchId);
                    if (hasilTerbaik) resolve(hasilTerbaik);
                    else reject(new Error('Waktu habis, sinyal GPS tidak ditemukan'));
                }
            },
            function (error) { reject(error); },
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxTunggu }
        );
    });
}

function hitungJarak(lat1, lng1, lat2, lng2) {
    var R    = 6371000;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLng = (lng2 - lng1) * Math.PI / 180;
    var a    = Math.sin(dLat / 2) * Math.sin(dLat / 2)
             + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
             * Math.sin(dLng / 2) * Math.sin(dLng / 2);
    var c    = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

$('#btnAmbilLokasi').on('click', function () {
    if (!tokoSaatIni) return;

    $('#statusMencariGps').removeClass('d-none');
    $('#btnAmbilLokasi').prop('disabled', true);
    $('#hasilVerifikasi').addClass('d-none');

    getAccuratePosition(50, 20000)
        .then(function (posisi) {
            var latSales = posisi.coords.latitude;
            var lngSales = posisi.coords.longitude;
            var akurasiSales = posisi.coords.accuracy;

            if (akurasiSales > 500) {
                alert('Akurasi GPS terlalu rendah (' + Math.round(akurasiSales) + ' meter). Gunakan perangkat dengan GPS aktif.');
                return;
            }

            var jarak = hitungJarak(
                parseFloat(tokoSaatIni.latitude),
                parseFloat(tokoSaatIni.longitude),
                latSales,
                lngSales
            );

            var batas       = 300;
            var thresholdEf = batas + parseFloat(tokoSaatIni.accuracy) + akurasiSales;
            var diterima    = jarak <= thresholdEf;

            $('#salesLat').text(latSales.toFixed(6));
            $('#salesLng').text(lngSales.toFixed(6));
            $('#salesAkurasi').text(akurasiSales.toFixed(1) + ' meter');
            $('#jarakAktual').text(Math.round(jarak) + ' meter');
            $('#thresholdEfektif').text(
                Math.round(thresholdEf) + ' meter' +
                ' (' + batas + ' + ' + Math.round(parseFloat(tokoSaatIni.accuracy)) + ' + ' + Math.round(akurasiSales) + ')'
            );

            if (diterima) {
                $('#kotakHasil').removeClass('alert-danger').addClass('alert-success');
                $('#teksHasil').text('DITERIMA');
                $('#keteranganHasil').text('Sales berada dalam radius yang diizinkan.');
            } else {
                $('#kotakHasil').removeClass('alert-success').addClass('alert-danger');
                $('#teksHasil').text('DITOLAK');
                $('#keteranganHasil').text('Sales berada di luar radius toko.');
            }

            $('#hasilVerifikasi').removeClass('d-none');
        })
        .catch(function (error) {
            alert('Gagal mendapatkan lokasi GPS: ' + error.message);
        })
        .finally(function () {
            $('#statusMencariGps').addClass('d-none');
            $('#btnAmbilLokasi').prop('disabled', false);
        });
});

$('#btnVerifikasiLain').on('click', function () {
    tokoSaatIni = null;
    $('#dataToko').addClass('d-none');
    $('#hasilVerifikasi').addClass('d-none');
    $('#statusAwal').text('Scan barcode toko terlebih dahulu untuk memulai verifikasi kunjungan.')
                   .removeClass('alert-danger').addClass('alert-secondary d-block');
    $('#inputKodeManual').val('');
});

$('#btnGeoloc').on('click', function () {
    var btn = $(this);
    btn.prop('disabled', true).text('Mencari sinyal GPS...');
    $('#statusGeoloc').removeClass('d-none alert-success alert-danger').addClass('alert-info').text('Mengambil koordinat GPS, harap tunggu...');

    getAccuratePosition(50, 20000)
        .then(function (posisi) {
            $('#inputLat').val(posisi.coords.latitude.toFixed(6));
            $('#inputLng').val(posisi.coords.longitude.toFixed(6));
            $('#inputAkurasi').val(posisi.coords.accuracy.toFixed(1));
            $('#statusGeoloc').removeClass('alert-info alert-danger').addClass('alert-success')
                .text('Koordinat berhasil diambil. Akurasi: ' + posisi.coords.accuracy.toFixed(1) + ' meter.');
        })
        .catch(function (error) {
            $('#statusGeoloc').removeClass('alert-info alert-success').addClass('alert-danger')
                .text('Gagal mengambil koordinat: ' + error.message);
        })
        .finally(function () {
            btn.prop('disabled', false).html('<i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Koordinat Otomatis');
        });
});

</script>
@endsection
