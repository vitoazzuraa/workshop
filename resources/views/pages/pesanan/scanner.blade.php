@extends('layouts.master')

@section('content')
<style>
    #reader__dashboard_section_swaplink { display: none !important; }
    #reader button {
        background-color: #b66dff;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
    #reader { border: none !important; }
</style>

<div class="row justify-content-center">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">Scan QR Code Pesanan</h4>
            </div>
            <div class="card-body">
                <div id="reader"></div>
                <div id="result" class="text-center mt-3 text-muted">
                    <i class="mdi mdi-camera-enhance d-block mb-2" style="font-size: 2rem;"></i>
                    Arahkan kamera ke QR Code pelanggan
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText) {
        html5QrcodeScanner.clear();
        window.location.href = "{{ url('/user/pesanan/periksa') }}/" + decodedText;
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 }
    );
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection