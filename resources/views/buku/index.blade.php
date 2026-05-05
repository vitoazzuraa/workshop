@extends('layouts.app')

@section('title', 'Buku')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-page-variant"></i>
        </span> Koleksi Buku
    </h3>
    <button class="btn btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="mdi mdi-plus me-1"></i> Tambah Buku
    </button>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Buku</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblBuku">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $b)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge badge-gradient-info">{{ $b->kode }}</span></td>
                                <td>{{ $b->judul }}</td>
                                <td>{{ $b->pengarang }}</td>
                                <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-gradient-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="{{ $b->idbuku }}"
                                        data-kode="{{ $b->kode }}"
                                        data-judul="{{ $b->judul }}"
                                        data-pengarang="{{ $b->pengarang }}"
                                        data-idkategori="{{ $b->idkategori }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-gradient-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalHapus"
                                        data-id="{{ $b->idbuku }}"
                                        data-judul="{{ $b->judul }}">
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
        <form action="{{ route('buku.store') }}" method="POST" id="formTambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Buku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('buku._form', ['buku' => null])
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
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Buku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('buku._form', ['buku' => null, 'prefix' => 'edit'])
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
                    <h5 class="modal-title"><i class="mdi mdi-alert me-2"></i>Hapus Buku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus buku <strong id="hapusJudul"></strong>?
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
    $('#tblBuku').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' } });

    $('#modalEdit').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formEdit').attr('action', '/buku/' + btn.dataset.id);
        $('#edit-kode').val(btn.dataset.kode);
        $('#edit-judul').val(btn.dataset.judul);
        $('#edit-pengarang').val(btn.dataset.pengarang);
        $('#edit-idkategori').val(btn.dataset.idkategori);
    });

    $('#modalHapus').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formHapus').attr('action', '/buku/' + btn.dataset.id);
        $('#hapusJudul').text(btn.dataset.judul);
    });
});
</script>
@endsection
