@extends('layouts.master')
@section('title', 'Daftar Buku')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Data Koleksi Buku</h4>
                    <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-icon-text">
                        <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Buku
                    </a>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> Kode </th>
                            <th> Judul </th>
                            <th> Pengarang </th>
                            <th> Kategori </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buku as $b)
                        <tr>
                            <td> {{ $b->kode }} </td>
                            <td> {{ $b->judul }} </td>
                            <td> {{ $b->pengarang }} </td>
                            <td> <label class="badge badge-info">{{ $b->kategori->nama_kategori }}</label> </td>
                            <td>
                                <a href="{{ route('buku.edit', $b->idbuku) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('buku.destroy', $b->idbuku) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
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
