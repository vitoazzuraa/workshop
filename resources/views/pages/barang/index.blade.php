@extends('layouts.master')
@section('title', 'Manajemen Barang')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Manajemen Barang</h4>
                    <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary">Tambah Barang</a>
                </div>

                <form action="{{ route('barang.print') }}" method="POST" target="_blank">
                    @csrf
                    <div class="row mb-3 bg-light p-3 rounded">
                        <div class="col-md-2">
                            <label>Kolom (X)</label>
                            <input type="number" name="x" class="form-control" min="1" max="5" value="1">
                        </div>
                        <div class="col-md-2">
                            <label>Baris (Y)</label>
                            <input type="number" name="y" class="form-control" min="1" max="8" value="1">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-success">Cetak Tag Harga</button>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $b)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $b->id_barang }}"></td>
                                <td>{{ $b->id_barang }}</td>
                                <td>{{ $b->nama }}</td>
                                <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('barang.edit', $b->id_barang) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $b->id_barang }}')">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                <form id="delete-form-global" action="" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('Hapus barang ini?')) {
            let form = document.getElementById('delete-form-global');
            form.action = '/barang/' + id;
            form.submit();
        }
    }
</script>
@endsection
