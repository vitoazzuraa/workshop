@extends('layouts.app')
@section('title', 'Tambah Customer — Foto Blob')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-camera"></i>
        </span> Tambah Customer — Foto ke Database (Blob)
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Metode 1: Foto disimpan sebagai BLOB</h4>
                <p class="card-description mb-4">
                    Kamera → Canvas → Base64 → dikirim via form hidden → disimpan ke kolom <code>foto_blob</code> di database.
                </p>

                <form action="{{ route('customer.simpan1') }}" method="POST" id="formCustomer1">
                    @csrf
                    <input type="hidden" name="foto_base64" id="fotoBase64">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                    </div>

                    <hr>
                    <h6 class="fw-semibold mb-3">Ambil Foto</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <video id="video" class="w-100 rounded border" autoplay playsinline style="height:200px;object-fit:cover;background:#000;"></video>
                        </div>
                        <div class="col-md-6">
                            <canvas id="canvas" class="w-100 rounded border" width="320" height="240" style="height:200px;object-fit:cover;background:#eee;"></canvas>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3 mb-4">
                        <button type="button" class="btn btn-gradient-info" id="btnKamera">
                            <i class="mdi mdi-camera me-1"></i> Aktifkan Kamera
                        </button>
                        <button type="button" class="btn btn-gradient-warning" id="btnCapture" disabled>
                            <i class="mdi mdi-camera-iris me-1"></i> Ambil Foto
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnRetake" style="display:none">
                            <i class="mdi mdi-refresh me-1"></i> Ulangi
                        </button>
                    </div>

                    <div class="alert alert-info d-none" id="fotoInfo">
                        <i class="mdi mdi-check-circle me-1"></i> Foto berhasil diambil dan siap disimpan.
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="button" id="btnSimpan" class="btn btn-gradient-primary"
                            onclick="submitWithSpinner('formCustomer1','btnSimpan')">
                            <i class="mdi mdi-content-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
let stream = null;

document.getElementById('btnKamera').addEventListener('click', async function () {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        document.getElementById('video').srcObject = stream;
        this.disabled = true;
        document.getElementById('btnCapture').disabled = false;
    } catch (e) {
        alert('Gagal mengakses kamera: ' + e.message);
    }
});

document.getElementById('btnCapture').addEventListener('click', function () {
    const video  = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx    = canvas.getContext('2d');

    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Ambil data base64 (tanpa prefix)
    const dataUrl  = canvas.toDataURL('image/jpeg', 0.8);
    const base64   = dataUrl.split(',')[1];
    document.getElementById('fotoBase64').value = base64;
    document.getElementById('fotoInfo').classList.remove('d-none');
    document.getElementById('btnRetake').style.display = 'inline-block';
    this.disabled = true;

    // Stop kamera
    if (stream) stream.getTracks().forEach(t => t.stop());
});

document.getElementById('btnRetake').addEventListener('click', async function () {
    document.getElementById('fotoBase64').value = '';
    document.getElementById('fotoInfo').classList.add('d-none');
    this.style.display = 'none';
    document.getElementById('btnCapture').disabled = false;
    document.getElementById('btnKamera').disabled = false;

    // Clear canvas
    const ctx = document.getElementById('canvas').getContext('2d');
    ctx.clearRect(0, 0, 320, 240);
});
</script>
@endsection
