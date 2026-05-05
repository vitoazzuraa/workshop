@extends('layouts.app')
@section('title', 'Riwayat Penjualan')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-receipt"></i>
        </span> Riwayat Penjualan
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Semua Transaksi</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblPenjualan">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Jumlah Item</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $p)
                            <tr>
                                <td><span class="badge badge-gradient-primary">{{ $p->id_penjualan }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y H:i') }}</td>
                                <td>{{ $p->detail->sum('jumlah') }}</td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-gradient-info btnDetail"
                                        data-id="{{ $p->id_penjualan }}">
                                        <i class="mdi mdi-eye me-1"></i>Detail
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

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-receipt me-2"></i>Detail Penjualan <span id="detailId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm mb-3">
                    <tr><td class="text-muted" style="width:35%">Tanggal</td><td id="detailTanggal"></td></tr>
                    <tr><td class="text-muted">Total</td><td class="fw-bold text-success" id="detailTotal"></td></tr>
                </table>
                <h6 class="fw-semibold">Rincian Barang</h6>
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr><th>Barang</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody id="detailBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $penjualanJs = $data->keyBy('id_penjualan')->map(function($p) {
        return [
            'id'       => $p->id_penjualan,
            'tanggal'  => \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y H:i'),
            'total'    => number_format($p->total, 0, ',', '.'),
            'detail'   => $p->detail->map(function($d) {
                return [
                    'nama'     => $d->barang->nama ?? $d->id_barang,
                    'jumlah'   => $d->jumlah,
                    'subtotal' => $d->subtotal,
                ];
            })->values(),
        ];
    });
@endphp

@section('js-page')
<script>
var penjualanData = @json($penjualanJs);

$(function () {
    $('#tblPenjualan').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, targets: [4] }]
    });

    $(document).on('click', '.btnDetail', function () {
        var id = $(this).data('id');
        var p  = penjualanData[id];
        if (!p) return;

        $('#detailId').text('#' + p.id);
        $('#detailTanggal').text(p.tanggal);
        $('#detailTotal').text('Rp ' + p.total);

        var rows = '';
        $.each(p.detail, function (i, d) {
            rows += '<tr>'
                + '<td>' + d.nama + '</td>'
                + '<td class="text-center">' + d.jumlah + '</td>'
                + '<td class="text-end">Rp ' + parseInt(d.subtotal).toLocaleString('id-ID') + '</td>'
                + '</tr>';
        });
        $('#detailBody').html(rows);

        $('#modalDetail').modal('show');
    });
});
</script>
@endsection
