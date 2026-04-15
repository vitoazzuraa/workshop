@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
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
                            <label>Kategori</label>
                            <select name="idkategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->idkategori }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Upload Gambar</label>
                            <input type="file" name="gambar" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
                        <a href="{{ route('user.menu.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
