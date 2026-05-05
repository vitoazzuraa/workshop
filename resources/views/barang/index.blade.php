@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-package-variant"></i>
        </span> Data Barang
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('barang.cetak.form') }}" class="btn btn-gradient-info btn-sm">
            <i class="mdi mdi-printer me-1"></i> Cetak Label
        </a>
        <button class="btn btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="mdi mdi-plus me-1"></i> Tambah Barang
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barang</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblBarang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $b)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge badge-gradient-primary">{{ $b->id_barang }}</span></td>
                                <td>{{ $b->nama }}</td>
                                <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->timestamp)->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-gradient-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="{{ $b->id_barang }}"
                                        data-nama="{{ $b->nama }}"
                                        data-harga="{{ $b->harga }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-gradient-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalHapus"
                                        data-id="{{ $b->id_barang }}"
                                        data-nama="{{ $b->nama }}">
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

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('barang.store') }}" method="POST" id="formTambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Barang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required maxlength="50" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control" required min="0" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnTambah" class="btn btn-gradient-primary"
                        onclick="submitWithSpinner('formTambah','btnTambah')">
                        <i class="mdi mdi-content-save me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Barang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" id="editHarga" class="form-control" required min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnEdit" class="btn btn-gradient-primary"
                        onclick="submitWithSpinner('formEdit','btnEdit')">
                        <i class="mdi mdi-content-save me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <form id="formHapus" method="POST">
            @csrf @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title"><i class="mdi mdi-alert me-2"></i>Hapus Barang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus barang <strong id="hapusNama"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnHapus" class="btn btn-gradient-danger"
                        onclick="submitWithSpinner('formHapus','btnHapus')">
                        <i class="mdi mdi-delete me-1"></i>Hapus
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js-page')
<script>
$(function () {
    $('#tblBarang').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' } });

    $('#modalEdit').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formEdit').attr('action', '/barang/' + btn.dataset.id);
        $('#editNama').val(btn.dataset.nama);
        $('#editHarga').val(btn.dataset.harga);
    });

    $('#modalHapus').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formHapus').attr('action', '/barang/' + btn.dataset.id);
        $('#hapusNama').text(btn.dataset.nama);
    });
});
</script>
@endsection
