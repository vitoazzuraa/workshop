@extends('layouts.app')

@section('title', 'Cetak Label Harga')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-printer"></i>
        </span> Cetak Label Harga
    </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pengaturan Cetak</h4>
                <form action="{{ route('barang.cetak.pdf') }}" method="POST" id="formCetak">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mulai dari kolom (X)</label>
                        <input type="number" name="start_x" class="form-control" value="1" min="1" max="5" required>
                        <small class="text-muted">1–5 (label 5 kolom per baris)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mulai dari baris (Y)</label>
                        <input type="number" name="start_y" class="form-control" value="1" min="1" max="8" required>
                        <small class="text-muted">1–8 (label 8 baris per halaman)</small>
                    </div>
                    <hr>
                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnPilihSemua">
                            Pilih Semua
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnBatalSemua">
                            Batal Semua
                        </button>
                    </div>
                    <button type="button" id="btnCetak" class="btn btn-gradient-primary w-100"
                        onclick="submitWithSpinner('formCetak','btnCetak')">
                        <i class="mdi mdi-file-pdf me-1"></i> Cetak PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Barang</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblCetak">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="checkAll" class="form-check-input">
                                </th>
                                <th>ID Barang</th>
                                <th>Nama</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $b)
                            <tr>
                                <td>
                                    <input type="checkbox" name="id_barang[]"
                                        value="{{ $b->id_barang }}"
                                        class="form-check-input check-barang"
                                        form="formCetak">
                                </td>
                                <td><span class="badge badge-gradient-primary">{{ $b->id_barang }}</span></td>
                                <td>{{ $b->nama }}</td>
                                <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
$(function () {
    $('#tblCetak').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
        columnDefs: [{ orderable: false, targets: 0 }]
    });

    $('#checkAll').on('change', function () {
        $('.check-barang').prop('checked', this.checked);
    });

    $('#btnPilihSemua').on('click', function () {
        $('.check-barang').prop('checked', true);
        $('#checkAll').prop('checked', true);
    });

    $('#btnBatalSemua').on('click', function () {
        $('.check-barang').prop('checked', false);
        $('#checkAll').prop('checked', false);
    });
});
</script>
@endsection
