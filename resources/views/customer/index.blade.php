@extends('layouts.app')
@section('title', 'Data Customer')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-group"></i>
        </span> Data Customer
    </h3>
    <div class="d-flex gap-2">
        <a href="{{ route('customer.tambah1') }}" class="btn btn-gradient-primary btn-sm">
            <i class="mdi mdi-camera me-1"></i> Tambah (Foto Blob)
        </a>
        <a href="{{ route('customer.tambah2') }}" class="btn btn-gradient-info btn-sm">
            <i class="mdi mdi-camera-plus me-1"></i> Tambah (Foto File)
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Customer</h4>
                <div class="table-responsive">
                    <table class="table table-hover" id="tblCustomer">
                        <thead>
                            <tr><th>No</th><th>Foto</th><th>Nama</th><th>Alamat</th><th>Dibuat</th></tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $c)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if($c->foto_blob)
                                        <img src="data:image/jpeg;base64,{{ base64_encode($c->foto_blob) }}"
                                            class="rounded" width="48" height="48" style="object-fit:cover">
                                    @elseif($c->foto_path)
                                        <img src="{{ asset('storage/' . $c->foto_path) }}"
                                            class="rounded" width="48" height="48" style="object-fit:cover">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                            style="width:48px;height:48px">
                                            <i class="mdi mdi-account text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $c->nama }}</td>
                                <td>{{ $c->alamat }}</td>
                                <td>{{ $c->created_at ? \Carbon\Carbon::parse($c->created_at)->format('d/m/Y') : '-' }}</td>
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
    $('#tblCustomer').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' } });
});
</script>
@endsection
