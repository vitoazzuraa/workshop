@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-multiple"></i>
        </span> Kategori
    </h3>
    <button class="btn btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="mdi mdi-plus me-1"></i> Tambah Kategori
    </button>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kategori</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblKategori">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kategori</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $k)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $k->nama_kategori }}</td>
                                <td>
                                    <button class="btn btn-sm btn-gradient-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="{{ $k->idkategori }}"
                                        data-nama="{{ $k->nama_kategori }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-gradient-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalHapus"
                                        data-id="{{ $k->idkategori }}"
                                        data-nama="{{ $k->nama_kategori }}">
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
        <form action="{{ route('kategori.store') }}" method="POST" id="formTambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control" required maxlength="100" autofocus>
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
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" id="editNama" class="form-control" required maxlength="100">
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
                    <h5 class="modal-title"><i class="mdi mdi-alert me-2"></i>Hapus Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus kategori <strong id="hapusNama"></strong>?
                    Semua buku dalam kategori ini juga akan terhapus.
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
    $('#tblKategori').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' } });

    $('#modalEdit').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formEdit').attr('action', '/kategori/' + btn.dataset.id);
        $('#editNama').val(btn.dataset.nama);
    });

    $('#modalHapus').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formHapus').attr('action', '/kategori/' + btn.dataset.id);
        $('#hapusNama').text(btn.dataset.nama);
    });
});
</script>
@endsection
