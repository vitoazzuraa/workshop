@extends('layouts.app')
@section('title', 'Scan QR Pesanan')

@section('style-page')
<style>
    #qrReader { width: 100%; max-width: 400px; margin: 0 auto; }
    #qrReader video { border-radius: 8px; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span> Scan QR Code Pesanan
    </h3>
</div>

<div class="row">
    <div class="col-md-5 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kamera QR</h4>
                <p class="card-description mb-3">Scan QR Code dari halaman status pesanan customer.</p>

                <div id="qrReader"></div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-gradient-primary" id="btnQrStart">
                        <i class="mdi mdi-camera me-1"></i> Mulai Scan
                    </button>
                    <button class="btn btn-outline-secondary d-none" id="btnQrStop">
                        <i class="mdi mdi-stop me-1"></i> Stop
                    </button>
                </div>
            </div>
        </div>

        {{-- Input manual --}}
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Input ID Pesanan Manual</h6>
                <div class="input-group">
                    <input type="number" id="manualId" class="form-control" placeholder="ID Pesanan...">
                    <button class="btn btn-gradient-info" id="btnManualScan">Cari</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Hasil Verifikasi</h4>

                <div id="hasilQr" class="alert alert-secondary">
                    Scan QR Code atau masukkan ID pesanan untuk verifikasi.
                </div>

                <div id="dataPesanan" class="d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="pesananId" class="mb-0"></h5>
                        <span id="pesananStatus" class="badge"></span>
                    </div>

                    <table class="table table-sm">
                        <tr><td>Nama</td><td id="pesananNama" class="fw-bold"></td></tr>
                        <tr><td>Total</td><td id="pesananTotal" class="fw-bold text-success"></td></tr>
                        <tr><td>Waktu</td><td id="pesananWaktu"></td></tr>
                    </table>

                    <h6 class="mt-3">Detail Pesanan:</h6>
                    <div id="pesananDetail"></div>

                    <button class="btn btn-gradient-success w-100 mt-3" id="btnScanBaru">
                        <i class="mdi mdi-refresh me-1"></i> Scan Pesanan Lain
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
let qrScanner = null;
let isQrScanning = false;

// Coba pakai file suara jika ada, fallback ke Web Audio API
const beepAudio = (function () {
    const a = new Audio('{{ asset("assets/audio/beep.mp3") }}');
    a.volume = 0.6;
    return a;
})();

function beep() {
    // Coba audio file dulu
    beepAudio.play().catch(function () {
        // Fallback: Web Audio API (tidak perlu file)
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

document.getElementById('btnQrStart').addEventListener('click', function () {
    if (isQrScanning) return;
    qrScanner = new Html5Qrcode('qrReader');
    qrScanner.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 220, height: 220 } },
        function (decodedText) {
            beep();
            qrScanner.stop().then(function () {
                isQrScanning = false;
                document.getElementById('btnQrStop').classList.add('d-none');
                document.getElementById('btnQrStart').classList.remove('d-none');
            });
            fetchPesanan(decodedText);
        },
        function () {}
    ).then(function () {
        isQrScanning = true;
        document.getElementById('btnQrStart').classList.add('d-none');
        document.getElementById('btnQrStop').classList.remove('d-none');
    }).catch(function (e) {
        alert('Kamera tidak bisa diakses: ' + e);
    });
});

document.getElementById('btnQrStop').addEventListener('click', function () {
    if (qrScanner && isQrScanning) {
        qrScanner.stop().then(function () {
            isQrScanning = false;
            document.getElementById('btnQrStop').classList.add('d-none');
            document.getElementById('btnQrStart').classList.remove('d-none');
        });
    }
});

document.getElementById('btnManualScan').addEventListener('click', function () {
    const id = document.getElementById('manualId').value.trim();
    if (id) { beep(); fetchPesanan(id); }
});
document.getElementById('manualId').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') { const id = this.value.trim(); if (id) { beep(); fetchPesanan(id); } }
});

document.getElementById('btnScanBaru').addEventListener('click', function () {
    document.getElementById('dataPesanan').classList.add('d-none');
    document.getElementById('hasilQr').className = 'alert alert-secondary';
    document.getElementById('hasilQr').textContent = 'Siap scan pesanan baru.';
    document.getElementById('manualId').value = '';
});

async function fetchPesanan(id) {
    const hasilEl = document.getElementById('hasilQr');
    hasilEl.className = 'alert alert-secondary';
    hasilEl.textContent = 'Memverifikasi pesanan #' + id + '...';
    document.getElementById('dataPesanan').classList.add('d-none');

    try {
        const res = await axios.get('/vendor/scan/hasil/' + encodeURIComponent(id));
        if (res.data.status === 'success') {
            const p = res.data.data;

            document.getElementById('pesananId').textContent = 'Pesanan #' + p.idpesanan;
            const statusBadge = document.getElementById('pesananStatus');
            if (p.status_bayar == 1) {
                statusBadge.className = 'badge badge-gradient-success';
                statusBadge.textContent = 'LUNAS ✓';
                hasilEl.className = 'alert alert-success';
                hasilEl.textContent = '✓ Pesanan valid dan sudah LUNAS';
            } else {
                statusBadge.className = 'badge badge-gradient-warning';
                statusBadge.textContent = 'BELUM BAYAR';
                hasilEl.className = 'alert alert-warning';
                hasilEl.textContent = '⚠ Pesanan belum dibayar!';
            }

            document.getElementById('pesananNama').textContent = p.nama;
            document.getElementById('pesananTotal').textContent = 'Rp ' + parseInt(p.total).toLocaleString('id-ID');
            document.getElementById('pesananWaktu').textContent = p.timestamp || '-';

            // Detail
            let detailHtml = '<ul class="list-group list-group-flush">';
            (p.detail || []).forEach(function (d) {
                const menu = d.menu ? d.menu.nama_menu : '-';
                const subtotal = parseInt(d.subtotal).toLocaleString('id-ID');
                detailHtml += `<li class="list-group-item px-0 d-flex justify-content-between">
                    <span>${menu} × ${d.jumlah}${d.catatan ? ' <small class="text-muted">('+d.catatan+')</small>' : ''}</span>
                    <span>Rp ${subtotal}</span>
                </li>`;
            });
            detailHtml += '</ul>';
            document.getElementById('pesananDetail').innerHTML = detailHtml;
            document.getElementById('dataPesanan').classList.remove('d-none');
        } else {
            hasilEl.className = 'alert alert-danger';
            hasilEl.textContent = '✗ ' + res.data.message;
        }
    } catch (e) {
        hasilEl.className = 'alert alert-danger';
        hasilEl.textContent = 'Kesalahan koneksi.';
    }
}
</script>
@endsection
