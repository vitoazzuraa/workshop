<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice Pesanan #{{ $pesanan->idpesanan }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body { background: #f4f6f9; }
        .invoice-header { background: linear-gradient(135deg, #6f42c1, #a855f7); color: #fff; padding: 20px 0; text-align: center; margin-bottom: 30px; }
        .qr-wrapper { text-align: center; padding: 16px 0; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .invoice-header { background: #6f42c1 !important; -webkit-print-color-adjust: exact; }
            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>
<div class="invoice-header no-print">
    <h3 class="mb-0"><i class="mdi mdi-receipt me-2"></i>Invoice Pesanan</h3>
    <small>Tunjukkan QR Code ini ke kasir untuk verifikasi</small>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    {{-- Header Invoice --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="mdi mdi-store text-primary me-1"></i>Kantin Sistem
                            </h5>
                            <p class="text-muted small mb-0">{{ $pesanan->timestamp ?? now()->format('d/m/Y H:i') }}</p>
                        </div>
                        <span id="statusBadge" class="badge fs-6 px-3 py-2 {{ $pesanan->status_bayar ? 'badge-gradient-success' : 'badge-gradient-warning' }}">
                            {{ $pesanan->status_bayar ? 'LUNAS ✓' : 'BELUM BAYAR' }}
                        </span>
                    </div>

                    <hr>

                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width:35%">No. Pesanan</td>
                            <td class="fw-bold">#{{ $pesanan->idpesanan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama</td>
                            <td class="fw-bold">{{ $pesanan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Waktu</td>
                            <td>{{ $pesanan->timestamp ?? '-' }}</td>
                        </tr>
                    </table>

                    <hr>

                    {{-- Detail Item --}}
                    <h6 class="fw-semibold mb-2">Rincian Pesanan</h6>
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center" style="width:50px">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detail as $d)
                            <tr>
                                <td>
                                    {{ $d->menu->nama_menu ?? '–' }}
                                    @if($d->catatan)
                                        <br><small class="text-muted">{{ $d->catatan }}</small>
                                    @endif
                                    <br><small class="text-muted">@ Rp {{ number_format($d->harga, 0, ',', '.') }}</small>
                                </td>
                                <td class="text-center">{{ $d->jumlah }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="2" class="fw-bold text-end">Total</td>
                                <td class="fw-bold text-success text-end fs-6">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <hr>

                    {{-- QR Code untuk verifikasi vendor --}}
                    <div class="qr-wrapper">
                        <p class="text-muted small mb-2">
                            <i class="mdi mdi-qrcode me-1"></i>Scan QR ini untuk verifikasi pembayaran
                        </p>
                        {!! QrCode::size(180)->generate((string) $pesanan->idpesanan) !!}
                        <p class="mt-2 fw-bold text-muted small">#{{ $pesanan->idpesanan }}</p>
                    </div>

                    {{-- Action buttons --}}
                    <div class="d-flex gap-2 mt-3 no-print">
                        <button onclick="window.print()" class="btn btn-outline-secondary flex-fill">
                            <i class="mdi mdi-printer me-1"></i> Cetak
                        </button>
                        <a href="{{ route('kantin.index') }}" class="btn btn-gradient-primary flex-fill">
                            <i class="mdi mdi-plus me-1"></i> Pesan Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@if(!$pesanan->status_bayar)
<script>
// Poll setiap 4 detik sampai status_bayar = 1
const pollInterval = setInterval(async function () {
    try {
        const res = await axios.get('/kantin/cek-status/{{ $pesanan->idpesanan }}');
        if (res.data.status_bayar == 1) {
            clearInterval(pollInterval);
            const badge = document.getElementById('statusBadge');
            badge.className = 'badge badge-gradient-success fs-6 px-3 py-2';
            badge.textContent = 'LUNAS ✓';
        }
    } catch (e) { /* abaikan error polling */ }
}, 4000);
// Hentikan polling setelah 5 menit
setTimeout(() => clearInterval(pollInterval), 300000);
</script>
@endif
</body>
</html>
