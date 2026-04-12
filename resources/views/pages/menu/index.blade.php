@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Manajemen Menu Saya</h4>
                    <a href="{{ route('user.menu.create') }}" class="btn btn-gradient-primary">Tambah Menu</a>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> Foto </th>
                            <th> Nama Menu </th>
                            <th> Harga </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td class="text-center">
                                @if($menu->path_gambar)
                                    <img src="{{ asset('storage/' . $menu->path_gambar) }}" style="width: 50px; height: 50px; border-radius: 5px;">
                                @else
                                    <img src="{{ asset('assets/images/no-image.jpg') }}" style="width: 50px; height: 50px; border-radius: 5px;">
                                @endif
                            </td>
                            <td> {{ $menu->nama_menu }} </td>
                            <td> Rp {{ number_format($menu->harga) }} </td>
                            <td>
                                <a href="{{ route('user.menu.edit', $menu->idmenu) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('user.menu.destroy', $menu->idmenu) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
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
