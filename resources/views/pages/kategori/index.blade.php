@extends('layouts.master')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Daftar Kategori Buku</h4>
                    <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-fw">
                        <i class="mdi mdi-plus"></i> Tambah Kategori
                    </a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> No </th>
                            <th> Nama Kategori </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategori as $index => $k)
                        <tr>
                            <td> {{ $index + 1 }} </td>
                            <td> {{ $k->nama_kategori }} </td>
                            <td>
                                <a href="{{ route('kategori.edit', $k->idkategori) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('kategori.destroy', $k->idkategori) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
