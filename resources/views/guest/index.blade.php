@extends('layouts.app')
@section('title', 'Data Guest Kantin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-multiple"></i>
        </span> Data Guest Kantin
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Guest Pemesan</h4>
                <p class="card-description">Customer yang memesan melalui halaman kantin tanpa login.</p>

                <div class="table-responsive mt-3">
                    <table id="guestTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th class="text-center">Total Pesanan</th>
                                <th class="text-end">Total Belanja</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $i => $g)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $g->nama }}</td>
                                <td>{{ $g->no_hp ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-gradient-primary">{{ $g->pesanan_count }}</span>
                                </td>
                                <td class="text-end fw-semibold text-success">
                                    Rp {{ number_format($g->pesanan_sum_total ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-muted small">{{ $g->created_at->format('d/m/Y H:i') }}</td>
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
$(document).ready(function () {
    $('#guestTable').DataTable({
        order: [[5, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ guest',
            paginate: { previous: '‹', next: '›' },
        },
    });
});
</script>
@endsection
