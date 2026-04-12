@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tambah Menu Baru</h4>
        <form action="{{ route('user.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Upload Gambar</label>
                <input type="file" name="gambar" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
