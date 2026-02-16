@extends('layouts.master')
@section('title', 'Manajemen Buku')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Koleksi Buku</h4>
            <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kode Buku</label>
                            <input type="text" name="kode" class="form-control" placeholder="NV-01" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Judul Buku</label>
                            <input type="text" name="judul" class="form-control" placeholder="Judul" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pengarang</label>
                            <input type="text" name="pengarang" class="form-control" placeholder="Nama Pengarang" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="idkategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->idkategori }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient-primary btn-fw">Simpan Koleksi</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Buku</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> Kode </th>
                            <th> Judul </th>
                            <th> Pengarang </th>
                            <th> Kategori </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buku as $b)
                        <tr>
                            <td> {{ $b->kode }} </td>
                            <td> {{ $b->judul }} </td>
                            <td> {{ $b->pengarang }} </td>
                            <td> <label class="badge badge-info">{{ $b->kategori->nama_kategori }}</label> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection