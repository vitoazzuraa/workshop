@extends('layouts.master')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Kategori</h4>
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" id="nama_kategori" placeholder="Contoh: Novel" required>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Kategori</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategori as $item)
                        <tr>
                            <td>{{ $item->idkategori }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection