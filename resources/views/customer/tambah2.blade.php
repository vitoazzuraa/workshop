@extends('layouts.app')
@section('title', 'Tambah Customer — Foto File')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-camera-plus"></i>
        </span> Tambah Customer — Foto disimpan sebagai File
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-7 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Metode 2: Foto disimpan sebagai File</h4>
                <p class="card-description mb-4">
                    Kamera → Canvas → <code>canvas.toBlob()</code> → FormData → multipart upload → disimpan ke storage, path ke kolom <code>foto_path</code>.
                </p>

                <form action="{{ route('customer.simpan2') }}" method="POST" enctype="multipart/form-data" id="formCustomer2">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                    </div>

                    {{-- Bisa upload file manual ATAU pakai kamera --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Foto (opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" id="fileInput">
                        <small class="text-muted">Atau gunakan kamera di bawah</small>
                    </div>

                    <hr>
                    <h6 class="fw-semibold mb-3">Atau Ambil dari Kamera</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <video id="video2" class="w-100 rounded border" autoplay playsinline style="height:200px;object-fit:cover;background:#000;"></video>
                        </div>
                        <div class="col-md-6">
                            <canvas id="canvas2" class="w-100 rounded border" width="320" height="240" style="height:200px;object-fit:cover;background:#eee;"></canvas>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3 mb-4">
                        <button type="button" class="btn btn-gradient-info" id="btnKamera2">
                            <i class="mdi mdi-camera me-1"></i> Aktifkan Kamera
                        </button>
                        <button type="button" class="btn btn-gradient-warning" id="btnCapture2" disabled>
                            <i class="mdi mdi-camera-iris me-1"></i> Ambil Foto
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnRetake2" style="display:none">
                            <i class="mdi mdi-refresh me-1"></i> Ulangi
                        </button>
                    </div>

                    <div class="alert alert-info d-none" id="fotoInfo2">
                        <i class="mdi mdi-check-circle me-1"></i> Foto kamera berhasil diambil (akan mengganti upload file jika ada).
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="button" id="btnSimpan2" class="btn btn-gradient-primary" id="btnSimpan2">
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
let stream2 = null;
let blobFile = null;

document.getElementById('btnKamera2').addEventListener('click', async function () {
    try {
        stream2 = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        document.getElementById('video2').srcObject = stream2;
        this.disabled = true;
        document.getElementById('btnCapture2').disabled = false;
    } catch (e) {
        alert('Gagal mengakses kamera: ' + e.message);
    }
});

document.getElementById('btnCapture2').addEventListener('click', function () {
    const video  = document.getElementById('video2');
    const canvas = document.getElementById('canvas2');
    const ctx    = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Simpan sebagai Blob/File untuk FormData
    canvas.toBlob(function (blob) {
        blobFile = new File([blob], 'foto-kamera.jpg', { type: 'image/jpeg' });
        document.getElementById('fotoInfo2').classList.remove('d-none');
    }, 'image/jpeg', 0.8);

    document.getElementById('btnRetake2').style.display = 'inline-block';
    document.getElementById('btnCapture2').disabled = true;

    if (stream2) stream2.getTracks().forEach(t => t.stop());
});

document.getElementById('btnRetake2').addEventListener('click', function () {
    blobFile = null;
    this.style.display = 'none';
    document.getElementById('fotoInfo2').classList.add('d-none');
    document.getElementById('btnCapture2').disabled = false;
    document.getElementById('btnKamera2').disabled = false;
    const ctx = document.getElementById('canvas2').getContext('2d');
    ctx.clearRect(0, 0, 320, 240);
});

// Submit manual: inject blob ke FormData jika ada
document.getElementById('btnSimpan2').addEventListener('click', function () {
    const form = document.getElementById('formCustomer2');
    if (!form.checkValidity()) { form.reportValidity(); return; }

    if (blobFile) {
        // Ganti input file dengan blob dari kamera
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(blobFile);
        document.getElementById('fileInput').files = dataTransfer.files;
    }

    const restore = btnLoading(this);
    form.submit();
});
</script>
@endsection
