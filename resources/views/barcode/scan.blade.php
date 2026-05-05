@extends('layouts.app')
@section('title', 'Barcode Scanner')

@section('style-page')
<style>
    #reader { width: 100%; max-width: 500px; margin: 0 auto; }
    #reader video { border-radius: 8px; }
    .scan-result { font-size: 1.1rem; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span> Barcode Scanner
    </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Scan Barcode Barang</h4>
                <p class="card-description mb-3">Arahkan kamera ke barcode pada label harga barang.</p>

                <div id="reader"></div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-gradient-primary" id="btnStart">
                        <i class="mdi mdi-camera me-1"></i> Mulai Scan
                    </button>
                    <button class="btn btn-outline-secondary" id="btnStop" style="display:none">
                        <i class="mdi mdi-stop me-1"></i> Stop
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Hasil Scan</h4>

                <div id="hasilScan" class="alert alert-secondary">
                    Belum ada scan. Mulai scan barcode di sebelah kiri.
                </div>

                <div id="dataBarang" class="d-none">
                    <table class="table table-sm">
                        <tr><td>ID Barang</td><td id="resId" class="fw-bold"></td></tr>
                        <tr><td>Nama</td><td id="resNama" class="fw-bold"></td></tr>
                        <tr><td>Harga</td><td id="resHarga" class="fw-bold text-success"></td></tr>
                    </table>
                    <button class="btn btn-gradient-success btn-sm mt-2" id="btnScanLagi">
                        <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                    </button>
                </div>

                <hr>
                <h6 class="mt-3">Riwayat Scan</h6>
                <div id="riwayatScan" class="mt-2">
                    <p class="text-muted small">Belum ada riwayat</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Input manual (fallback) --}}
<div class="row">
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Manual</h4>
                <p class="card-description mb-3">Ketik kode barang secara manual jika kamera tidak tersedia.</p>
                <div class="input-group">
                    <input type="text" id="manualInput" class="form-control" placeholder="Ketik ID Barang...">
                    <button class="btn btn-gradient-info" id="btnManual">
                        <i class="mdi mdi-magnify"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;
let riwayat = [];
let isScanning = false;

const beepAudio = (function () {
    const a = new Audio('{{ asset("assets/audio/beep.mp3") }}');
    a.volume = 0.6;
    return a;
})();
function beep() {
    beepAudio.play().catch(function () {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain); gain.connect(ctx.destination);
            osc.frequency.value = 1200;
            gain.gain.setValueAtTime(0.5, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.2);
            osc.start(); osc.stop(ctx.currentTime + 0.2);
        } catch (e) {}
    });
}

document.getElementById('btnStart').addEventListener('click', function () {
    if (isScanning) return;
    html5QrCode = new Html5Qrcode('reader');
    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 250, height: 100 } },
        function (decodedText) {
            beep();
            html5QrCode.stop().then(function () {
                isScanning = false;
                document.getElementById('btnStop').style.display = 'none';
                document.getElementById('btnStart').style.display = 'inline-block';
            });
            fetchBarang(decodedText);
        },
        function (err) { /* scan error diabaikan */ }
    ).then(function () {
        isScanning = true;
        document.getElementById('btnStart').style.display = 'none';
        document.getElementById('btnStop').style.display = 'inline-block';
    }).catch(function (e) {
        alert('Kamera tidak bisa diakses: ' + e);
    });
});

document.getElementById('btnStop').addEventListener('click', function () {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(function () {
            isScanning = false;
            document.getElementById('btnStop').style.display = 'none';
            document.getElementById('btnStart').style.display = 'inline-block';
        });
    }
});

document.getElementById('btnManual').addEventListener('click', function () {
    const kode = document.getElementById('manualInput').value.trim();
    if (kode) { beep(); fetchBarang(kode); }
});

document.getElementById('manualInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        const kode = this.value.trim();
        if (kode) { beep(); fetchBarang(kode); }
    }
});

document.getElementById('btnScanLagi').addEventListener('click', function () {
    document.getElementById('dataBarang').classList.add('d-none');
    document.getElementById('hasilScan').className = 'alert alert-secondary';
    document.getElementById('hasilScan').textContent = 'Siap scan. Klik Mulai Scan.';
});

async function fetchBarang(kode) {
    const hasilEl = document.getElementById('hasilScan');
    hasilEl.className = 'alert alert-secondary';
    hasilEl.textContent = 'Mencari barang dengan kode: ' + kode + '...';

    try {
        const res = await axios.get('/barcode/hasil/' + encodeURIComponent(kode));
        if (res.data.status === 'success') {
            const b = res.data.data;
            document.getElementById('resId').textContent = b.id_barang;
            document.getElementById('resNama').textContent = b.nama;
            document.getElementById('resHarga').textContent = 'Rp ' + parseInt(b.harga).toLocaleString('id-ID');
            document.getElementById('dataBarang').classList.remove('d-none');
            hasilEl.className = 'alert alert-success';
            hasilEl.textContent = '✓ Barang ditemukan!';

            // Tambah ke riwayat
            riwayat.unshift({ kode: b.id_barang, nama: b.nama, waktu: new Date().toLocaleTimeString('id-ID') });
            if (riwayat.length > 10) riwayat.pop();
            renderRiwayat();
        } else {
            hasilEl.className = 'alert alert-danger';
            hasilEl.textContent = '✗ ' + res.data.message;
            document.getElementById('dataBarang').classList.add('d-none');
        }
    } catch (e) {
        hasilEl.className = 'alert alert-danger';
        hasilEl.textContent = 'Kesalahan koneksi.';
    }
}

function renderRiwayat() {
    const el = document.getElementById('riwayatScan');
    if (riwayat.length === 0) { el.innerHTML = '<p class="text-muted small">Belum ada riwayat</p>'; return; }
    el.innerHTML = '<ul class="list-group list-group-flush">' +
        riwayat.map(function (r) {
            return `<li class="list-group-item px-0 py-1 d-flex justify-content-between">
                <span><span class="badge badge-gradient-primary me-1">${r.kode}</span>${r.nama}</span>
                <small class="text-muted">${r.waktu}</small>
            </li>`;
        }).join('') + '</ul>';
}
</script>
@endsection
