@extends('layouts.master')
@section('title', 'Edit Buku')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Koleksi Buku</h4>
                <p class="card-description"> Ubah detail buku: {{ $buku->judul }} </p>

                <form class="forms-sample" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kode Buku</label>
                                <input type="text" name="kode" class="form-control" value="{{ $buku->kode }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Judul Buku</label>
                                <input type="text" name="judul" class="form-control" value="{{ $buku->judul }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pengarang</label>
                                <input type="text" name="pengarang" class="form-control" value="{{ $buku->pengarang }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="idkategori" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->idkategori }}" {{ $buku->idkategori == $k->idkategori ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-gradient-primary mr-2">Update Koleksi</button>
                            <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
