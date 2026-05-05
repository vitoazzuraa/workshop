@extends('layouts.app')
@section('title', 'Manajemen Menu')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-food"></i>
        </span> Manajemen Menu
        @if(session('user.role') === 'vendor')
            <small class="text-muted fs-6 ms-2">{{ session('user.nama_vendor') }}</small>
        @endif
    </h3>
    <button class="btn btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="mdi mdi-plus me-1"></i> Tambah Menu
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Menu</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblMenu">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Menu</th>
                                @if(session('user.role') === 'admin')
                                <th>Vendor</th>
                                @endif
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $m)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if($m->path_gambar)
                                        <img src="{{ asset('storage/' . $m->path_gambar) }}"
                                             alt="{{ $m->nama_menu }}"
                                             style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:50px;height:50px;border-radius:6px;background:#eee;display:flex;align-items:center;justify-content:center;">
                                            <i class="mdi mdi-image-off text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $m->nama_menu }}</td>
                                @if(session('user.role') === 'admin')
                                <td><span class="badge badge-gradient-info">{{ $m->vendor->nama_vendor ?? '-' }}</span></td>
                                @endif
                                <td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-gradient-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="{{ $m->idmenu }}"
                                        data-nama="{{ $m->nama_menu }}"
                                        data-harga="{{ $m->harga }}"
                                        data-gambar="{{ $m->path_gambar ? asset('storage/' . $m->path_gambar) : '' }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-gradient-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalHapus"
                                        data-id="{{ $m->idmenu }}"
                                        data-nama="{{ $m->nama_menu }}">
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
        <form action="{{ route('vendor.menu.store') }}" method="POST" enctype="multipart/form-data" id="formTambah">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if(session('user.role') === 'admin')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Vendor <span class="text-danger">*</span></label>
                        <select name="idvendor" class="form-select" required>
                            @foreach($vendors as $v)
                            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" name="nama_menu" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Menu</label>
                        <input type="file" name="foto" id="fotoTambah" class="form-control" accept="image/*">
                        <div class="mt-2" id="previewTambahWrap" style="display:none;">
                            <img id="previewTambah" src="" alt="preview"
                                 style="max-height:120px;border-radius:8px;object-fit:cover;">
                        </div>
                        <small class="text-muted">Maks. 2MB. Format: JPG, PNG, GIF.</small>
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
        <form id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Menu</label>
                        <input type="text" name="nama_menu" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" id="editHarga" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Menu</label>
                        <div id="editCurrentFotoWrap" class="mb-2" style="display:none;">
                            <p class="text-muted small mb-1">Foto saat ini:</p>
                            <img id="editCurrentFoto" src="" alt="current"
                                 style="max-height:100px;border-radius:8px;object-fit:cover;">
                        </div>
                        <input type="file" name="foto" id="fotoEdit" class="form-control" accept="image/*">
                        <div class="mt-2" id="previewEditWrap" style="display:none;">
                            <img id="previewEdit" src="" alt="preview"
                                 style="max-height:120px;border-radius:8px;object-fit:cover;">
                        </div>
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
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
                    <h5 class="modal-title"><i class="mdi mdi-alert me-2"></i>Hapus Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus menu <strong id="hapusNama"></strong>?
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
    $('#tblMenu').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' } });

    // Preview foto tambah
    $('#fotoTambah').on('change', function () {
        const file = this.files[0];
        if (file) {
            $('#previewTambah').attr('src', URL.createObjectURL(file));
            $('#previewTambahWrap').show();
        }
    });

    // Preview foto edit
    $('#fotoEdit').on('change', function () {
        const file = this.files[0];
        if (file) {
            $('#previewEdit').attr('src', URL.createObjectURL(file));
            $('#previewEditWrap').show();
        }
    });

    $('#modalEdit').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formEdit').attr('action', '/vendor/menu/' + btn.dataset.id);
        $('#editNama').val(btn.dataset.nama);
        $('#editHarga').val(btn.dataset.harga);
        $('#fotoEdit').val('');
        $('#previewEditWrap').hide();

        if (btn.dataset.gambar) {
            $('#editCurrentFoto').attr('src', btn.dataset.gambar);
            $('#editCurrentFotoWrap').show();
        } else {
            $('#editCurrentFotoWrap').hide();
        }
    });

    $('#modalHapus').on('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        $('#formHapus').attr('action', '/vendor/menu/' + btn.dataset.id);
        $('#hapusNama').text(btn.dataset.nama);
    });
});
</script>
@endsection
