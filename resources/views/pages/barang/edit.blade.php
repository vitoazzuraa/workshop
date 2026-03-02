@extends('layouts.master')
@section('title', 'Edit Barang')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Data Barang</h4>
                <form class="forms-sample" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" class="form-control" value="{{ $barang->id_barang }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $barang->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="{{ $barang->harga }}" required>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Update</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
