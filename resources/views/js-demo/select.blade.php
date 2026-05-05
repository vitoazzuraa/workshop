@extends('layouts.app')
@section('title', 'Demo Select2')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-form-select"></i>
        </span> Demo Select & Select2
    </h3>
</div>

<div class="row">
    {{-- Select biasa --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select HTML Biasa</h4>
                <p class="card-description mb-3">Contoh select standar HTML dengan Bootstrap styling.</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Provinsi</label>
                    <select class="form-select" id="selProvinsi">
                        <option value="">-- Pilih --</option>
                        <option value="JKT">DKI Jakarta</option>
                        <option value="JBR">Jawa Barat</option>
                        <option value="JTG">Jawa Tengah</option>
                        <option value="JTM">Jawa Timur</option>
                        <option value="DIY">DI Yogyakarta</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Multiple (Ctrl+click)</label>
                    <select class="form-select" multiple size="5" id="selMultiple">
                        <option>Senin</option>
                        <option>Selasa</option>
                        <option>Rabu</option>
                        <option>Kamis</option>
                        <option>Jumat</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Dipilih:</label>
                    <div id="outputPilihan" class="alert alert-secondary py-2">–</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Select2 basic --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select2 — Searchable</h4>
                <p class="card-description mb-3">Select2 dengan fitur pencarian dan placeholder.</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Kota (searchable)</label>
                    <select class="form-select" id="select2Kota" style="width:100%">
                        <option></option>
                        @php
                        $kota = ['Jakarta Pusat','Jakarta Selatan','Jakarta Barat','Jakarta Utara','Jakarta Timur',
                                 'Bandung','Surabaya','Medan','Semarang','Makassar',
                                 'Palembang','Tangerang','Depok','Bekasi','Bogor',
                                 'Yogyakarta','Malang','Padang','Pekanbaru','Denpasar'];
                        @endphp
                        @foreach($kota as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Kategori (multiple)</label>
                    <select class="form-select" id="select2Multi" multiple style="width:100%">
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="snack">Snack</option>
                        <option value="elektronik">Elektronik</option>
                        <option value="fashion">Fashion</option>
                        <option value="olahraga">Olahraga</option>
                    </select>
                </div>

                <div id="output2" class="alert alert-secondary py-2 mt-3">Pilih sesuatu...</div>
            </div>
        </div>
    </div>

    {{-- Select2 dengan data dinamis --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select2 — Tagging (Free Input)</h4>
                <p class="card-description mb-3">User bisa mengetik tag baru yang belum ada di list.</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tag Produk</label>
                    <select class="form-select" id="select2Tag" multiple style="width:100%">
                        <option value="promo" selected>Promo</option>
                        <option value="baru">Baru</option>
                        <option value="best-seller" selected>Best Seller</option>
                    </select>
                </div>
                <small class="text-muted">Ketik nama tag lalu tekan Enter untuk menambah tag baru.</small>
            </div>
        </div>
    </div>

    {{-- Select2 dependent --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Dependent (Tanpa AJAX)</h4>
                <p class="card-description mb-3">Pilih kategori → opsi sub-kategori berubah.</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select class="form-select" id="depKategori">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="elektronik">Elektronik</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sub-Kategori</label>
                    <select class="form-select" id="depSub" disabled>
                        <option value="">-- Pilih Kategori dulu --</option>
                    </select>
                </div>
                <div id="depOutput" class="alert alert-secondary py-2">–</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
$(function () {
    // === Select biasa ===
    function updatePilihan() {
        const prov   = $('#selProvinsi option:selected').text();
        const multi  = $('#selMultiple option:selected').map(function(){ return $(this).text(); }).get().join(', ');
        const out    = [prov !== '-- Pilih --' ? 'Provinsi: ' + prov : '', multi ? 'Hari: ' + multi : ''].filter(Boolean);
        $('#outputPilihan').text(out.length ? out.join(' | ') : '–');
    }
    $('#selProvinsi, #selMultiple').on('change', updatePilihan);

    // === Select2 searchable ===
    $('#select2Kota').select2({
        placeholder: 'Cari kota...',
        allowClear: true,
    });

    // === Select2 multiple ===
    $('#select2Multi').select2({
        placeholder: 'Pilih kategori...',
    });

    // Update output saat pilihan berubah
    $('#select2Kota, #select2Multi').on('change', function () {
        const kota = $('#select2Kota').val() || '–';
        const multi = ($('#select2Multi').val() || []).join(', ') || '–';
        $('#output2').html(`Kota: <strong>${kota}</strong> | Kategori: <strong>${multi}</strong>`);
    });

    // === Select2 tagging ===
    $('#select2Tag').select2({
        tags: true,
        placeholder: 'Tambah tag...',
        tokenSeparators: [',', ' '],
    });

    // === Select dependent ===
    const subData = {
        makanan: ['Nasi','Mie','Soto','Sate','Bakso'],
        minuman: ['Kopi','Teh','Jus','Es Campur','Susu'],
        elektronik: ['Smartphone','Laptop','Tablet','Headphone','Smart TV'],
    };

    $('#depKategori').on('change', function () {
        const val = $(this).val();
        const $sub = $('#depSub');
        $sub.empty().prop('disabled', !val);
        if (!val) { $sub.append('<option>-- Pilih Kategori dulu --</option>'); return; }
        $sub.append('<option value="">-- Pilih Sub-Kategori --</option>');
        (subData[val] || []).forEach(function (s) {
            $sub.append(`<option value="${s}">${s}</option>`);
        });
        $('#depOutput').text('–');
    });

    $('#depSub').on('change', function () {
        const sub = $(this).val();
        if (sub) {
            const cat = $('#depKategori option:selected').text();
            $('#depOutput').html(`Dipilih: <strong>${cat}</strong> → <strong>${sub}</strong>`);
        }
    });
});
</script>
@endsection
