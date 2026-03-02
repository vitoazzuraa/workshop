@extends('layouts.master')
@section('title', 'Tambah Buku')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Koleksi Buku Baru</h4>
                <p class="card-description"> Masukkan detail informasi buku di bawah ini. </p>

                <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Buku</label>
                                <input type="text" name="kode" class="form-control" placeholder="Contoh: NV-01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="idkategori" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->idkategori }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan Judul Lengkap" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" placeholder="Nama Penulis" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-gradient-primary me-2">Simpan Buku</button>
                        <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
