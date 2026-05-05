@extends('layouts.app')
@section('title', 'Pesanan Lunas')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-bag-checked"></i>
        </span> Pesanan Lunas
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pesanan Telah Dibayar</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblPesanan">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Total</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $p)
                            <tr>
                                <td><span class="badge badge-gradient-success">#{{ $p->idpesanan }}</span></td>
                                <td>{{ $p->nama }}</td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-gradient-info btnDetail"
                                        data-id="{{ $p->idpesanan }}">
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
                <h5 class="modal-title"><i class="mdi mdi-receipt me-2"></i>Detail Pesanan <span id="detailId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm mb-3">
                    <tr><td class="text-muted" style="width:35%">Nama</td><td class="fw-semibold" id="detailNama"></td></tr>
                    <tr><td class="text-muted">Waktu</td><td id="detailWaktu"></td></tr>
                </table>
                <h6 class="fw-semibold">Rincian Item</h6>
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr><th>Menu</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody id="detailBody"></tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="2" class="fw-bold text-end">Total</td>
                            <td class="fw-bold text-success text-end" id="detailTotal"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $pesananJs = $data->keyBy('idpesanan')->map(function($p) {
        return [
            'id'     => $p->idpesanan,
            'nama'   => $p->nama,
            'total'  => number_format($p->total, 0, ',', '.'),
            'waktu'  => \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y H:i'),
            'detail' => $p->detail->map(function($d) {
                return [
                    'menu'     => $d->menu?->nama_menu ?? '-',
                    'jumlah'   => $d->jumlah,
                    'subtotal' => $d->subtotal,
                    'catatan'  => $d->catatan ?? '',
                ];
            })->values(),
        ];
    });
@endphp

@section('js-page')
<script>
var pesananData = @json($pesananJs);

$(function () {
    $('#tblPesanan').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, targets: [4] }]
    });

    $(document).on('click', '.btnDetail', function () {
        var id = $(this).data('id');
        var p  = pesananData[id];
        if (!p) return;

        $('#detailId').text('#' + p.id);
        $('#detailNama').text(p.nama);
        $('#detailWaktu').text(p.waktu);
        $('#detailTotal').text('Rp ' + p.total);

        var rows = '';
        $.each(p.detail, function (i, d) {
            rows += '<tr>'
                + '<td>' + d.menu + (d.catatan ? '<br><small class="text-muted">' + d.catatan + '</small>' : '') + '</td>'
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
