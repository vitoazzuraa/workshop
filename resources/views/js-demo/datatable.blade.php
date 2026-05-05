@extends('layouts.app')
@section('title', 'Demo DataTables')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-table-large"></i>
        </span> Demo DataTables
    </h3>
</div>

<div class="row">
    {{-- DataTable dasar --}}
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">DataTable — Fitur Bawaan</h4>
                <p class="card-description mb-3">Search, sort, pagination otomatis dari library DataTables.</p>
                <div class="table-responsive">
                    <table id="dtDasar" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th><th>Nama Produk</th><th>Kategori</th>
                                <th>Harga</th><th>Stok</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $produk = [
                                ['Nasi Goreng Spesial','Makanan',15000,50,'Tersedia'],
                                ['Mie Ayam Bakso','Makanan',12000,30,'Tersedia'],
                                ['Es Teh Manis','Minuman',5000,100,'Tersedia'],
                                ['Jus Alpukat','Minuman',10000,20,'Tersedia'],
                                ['Roti Bakar Coklat','Snack',8000,40,'Tersedia'],
                                ['Soto Ayam','Makanan',14000,25,'Tersedia'],
                                ['Gado-Gado','Makanan',11000,15,'Habis'],
                                ['Air Mineral','Minuman',3000,200,'Tersedia'],
                                ['Pisang Goreng','Snack',6000,60,'Tersedia'],
                                ['Lontong Sayur','Makanan',9000,0,'Habis'],
                                ['Kopi Susu','Minuman',8000,45,'Tersedia'],
                                ['Teh Tarik','Minuman',7000,55,'Tersedia'],
                                ['Martabak Mini','Snack',12000,10,'Tersedia'],
                                ['Siomay','Snack',10000,35,'Tersedia'],
                                ['Bubur Ayam','Makanan',13000,20,'Tersedia'],
                            ];
                            @endphp
                            @foreach($produk as $i => $p)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $p[0] }}</td>
                                <td>{{ $p[1] }}</td>
                                <td>Rp {{ number_format($p[2],0,',','.') }}</td>
                                <td>{{ $p[3] }}</td>
                                <td>
                                    @if($p[4] === 'Tersedia')
                                        <span class="badge badge-gradient-success">{{ $p[4] }}</span>
                                    @else
                                        <span class="badge badge-gradient-danger">{{ $p[4] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable dengan tombol export --}}
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">DataTable — Column Visibility & Custom Filter</h4>
                <p class="card-description mb-3">Contoh custom render kolom dan filter dropdown per kolom.</p>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filterKategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            <option>Makanan</option>
                            <option>Minuman</option>
                            <option>Snack</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option>Tersedia</option>
                            <option>Habis</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="dtCustom" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th><th>Kategori</th><th>Harga</th>
                                <th>Stok</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produk as $p)
                            <tr>
                                <td>{{ $p[0] }}</td>
                                <td>{{ $p[1] }}</td>
                                <td data-order="{{ $p[2] }}">Rp {{ number_format($p[2],0,',','.') }}</td>
                                <td>{{ $p[3] }}</td>
                                <td>{{ $p[4] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
$(function () {
    // DataTable dasar
    $('#dtDasar').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
        pageLength: 5,
        lengthMenu: [5, 10, 25],
        order: [[0, 'asc']],
    });

    // DataTable custom dengan filter dropdown
    const dtCustom = $('#dtCustom').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
        pageLength: 5,
        columnDefs: [
            {
                targets: 4,
                render: function (data) {
                    const cls = data === 'Tersedia' ? 'badge-gradient-success' : 'badge-gradient-danger';
                    return `<span class="badge ${cls}">${data}</span>`;
                }
            }
        ]
    });

    // Filter kolom Kategori (index 1) dan Status (index 4)
    $('#filterKategori').on('change', function () {
        dtCustom.column(1).search($(this).val()).draw();
    });
    $('#filterStatus').on('change', function () {
        dtCustom.column(4).search($(this).val()).draw();
    });
});
</script>
@endsection
