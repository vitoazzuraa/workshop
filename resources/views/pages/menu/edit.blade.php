@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Menu</h4>
                    <form action="{{ route('user.menu.update', $menu->idmenu) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label>Nama Menu</label>
                            <input type="text" name="nama_menu" class="form-control" value="{{ $menu->nama_menu }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="idkategori" class="form-control" required>
                                @foreach ($kategori as $k)
                                    <option value="{{ $k->idkategori }}"
                                        {{ $menu->idkategori == $k->idkategori ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" value="{{ $menu->harga }}" required>
                        </div>
                        <div class="form-group">
                            <label>Ganti Foto (Kosongkan jika tidak ingin mengubah)</label>
                            @if ($menu->path_gambar)
                                <br><img src="{{ asset('storage/' . $menu->path_gambar) }}" width="100"
                                    class="mb-2 rounded">
                            @endif
                            <input type="file" name="gambar" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-gradient-primary mr-2">Update</button>
                        <a href="{{ route('user.menu.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
