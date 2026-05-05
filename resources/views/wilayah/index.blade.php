@extends('layouts.app')
@section('title', 'Demo Cascading Wilayah')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker"></i>
        </span> Demo Cascading Select — Wilayah Indonesia
    </h3>
</div>

<div class="row">
    {{-- Versi jQuery Ajax --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Versi jQuery <code>$.ajax()</code></h4>
                <p class="card-description mb-3">Cascading select menggunakan jQuery Ajax klasik.</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Provinsi</label>
                    <select class="form-select" id="ajax-provinsi">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsi as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kota / Kabupaten</label>
                    <select class="form-select" id="ajax-kota" disabled>
                        <option value="">-- Pilih Provinsi dulu --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kecamatan</label>
                    <select class="form-select" id="ajax-kecamatan" disabled>
                        <option value="">-- Pilih Kota dulu --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelurahan</label>
                    <select class="form-select" id="ajax-kelurahan" disabled>
                        <option value="">-- Pilih Kecamatan dulu --</option>
                    </select>
                </div>

                <div id="ajax-hasil" class="alert alert-secondary d-none"></div>
            </div>
        </div>
    </div>

    {{-- Versi Axios --}}
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Versi <code>axios</code></h4>
                <p class="card-description mb-3">Cascading select menggunakan Axios (Promise-based).</p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Provinsi</label>
                    <select class="form-select" id="axios-provinsi">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinsi as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kota / Kabupaten</label>
                    <select class="form-select" id="axios-kota" disabled>
                        <option value="">-- Pilih Provinsi dulu --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kecamatan</label>
                    <select class="form-select" id="axios-kecamatan" disabled>
                        <option value="">-- Pilih Kota dulu --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelurahan</label>
                    <select class="form-select" id="axios-kelurahan" disabled>
                        <option value="">-- Pilih Kecamatan dulu --</option>
                    </select>
                </div>

                <div id="axios-hasil" class="alert alert-secondary d-none"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-page')
<script>
// ====================================================
// Versi jQuery $.ajax()
// ====================================================
function resetSelect(id, placeholder) {
    $('#' + id).empty().append('<option value="">-- ' + placeholder + ' --</option>').prop('disabled', true);
}

$('#ajax-provinsi').on('change', function () {
    const id = $(this).val();
    resetSelect('ajax-kota', 'Pilih Kota dulu');
    resetSelect('ajax-kecamatan', 'Pilih Kota dulu');
    resetSelect('ajax-kelurahan', 'Pilih Kecamatan dulu');
    $('#ajax-hasil').addClass('d-none');
    if (!id) return;

    $.ajax({
        url: '/wilayah/kota/' + id,
        type: 'GET',
        success: function (res) {
            const $sel = $('#ajax-kota').empty().prop('disabled', false);
            $sel.append('<option value="">-- Pilih Kota --</option>');
            res.data.forEach(function (k) {
                $sel.append('<option value="' + k.id + '">' + k.nama + '</option>');
            });
        }
    });
});

$('#ajax-kota').on('change', function () {
    const id = $(this).val();
    resetSelect('ajax-kecamatan', 'Pilih Kecamatan dulu');
    resetSelect('ajax-kelurahan', 'Pilih Kecamatan dulu');
    if (!id) return;

    $.ajax({
        url: '/wilayah/kecamatan/' + id,
        type: 'GET',
        success: function (res) {
            const $sel = $('#ajax-kecamatan').empty().prop('disabled', false);
            $sel.append('<option value="">-- Pilih Kecamatan --</option>');
            res.data.forEach(function (k) {
                $sel.append('<option value="' + k.id + '">' + k.nama + '</option>');
            });
        }
    });
});

$('#ajax-kecamatan').on('change', function () {
    const id = $(this).val();
    resetSelect('ajax-kelurahan', 'Pilih Kelurahan dulu');
    if (!id) return;

    $.ajax({
        url: '/wilayah/kelurahan/' + id,
        type: 'GET',
        success: function (res) {
            const $sel = $('#ajax-kelurahan').empty().prop('disabled', false);
            $sel.append('<option value="">-- Pilih Kelurahan --</option>');
            res.data.forEach(function (k) {
                $sel.append('<option value="' + k.id + '">' + k.nama + '</option>');
            });
        }
    });
});

$('#ajax-kelurahan').on('change', function () {
    const kelurahan = $(this).find('option:selected').text();
    const kecamatan = $('#ajax-kecamatan option:selected').text();
    const kota = $('#ajax-kota option:selected').text();
    const provinsi = $('#ajax-provinsi option:selected').text();
    if ($(this).val()) {
        $('#ajax-hasil').removeClass('d-none')
            .html(`<strong>Hasil:</strong> ${kelurahan}, ${kecamatan}, ${kota}, ${provinsi}`);
    }
});

// ====================================================
// Versi Axios (async/await)
// ====================================================
async function axiosLoadOptions(url, selectId, placeholder) {
    const $sel = document.getElementById(selectId);
    $sel.innerHTML = '<option>Memuat...</option>';
    $sel.disabled = true;
    try {
        const res = await axios.get(url);
        $sel.innerHTML = `<option value="">-- ${placeholder} --</option>`;
        res.data.data.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nama;
            $sel.appendChild(opt);
        });
        $sel.disabled = false;
    } catch (e) {
        $sel.innerHTML = '<option>Gagal memuat</option>';
    }
}

document.getElementById('axios-provinsi').addEventListener('change', async function () {
    const id = this.value;
    ['axios-kota','axios-kecamatan','axios-kelurahan'].forEach(function (s) {
        document.getElementById(s).innerHTML = '<option value="">--</option>';
        document.getElementById(s).disabled = true;
    });
    document.getElementById('axios-hasil').classList.add('d-none');
    if (!id) return;
    await axiosLoadOptions('/wilayah/kota/' + id, 'axios-kota', 'Pilih Kota');
});

document.getElementById('axios-kota').addEventListener('change', async function () {
    const id = this.value;
    ['axios-kecamatan','axios-kelurahan'].forEach(function (s) {
        document.getElementById(s).innerHTML = '<option value="">--</option>';
        document.getElementById(s).disabled = true;
    });
    if (!id) return;
    await axiosLoadOptions('/wilayah/kecamatan/' + id, 'axios-kecamatan', 'Pilih Kecamatan');
});

document.getElementById('axios-kecamatan').addEventListener('change', async function () {
    const id = this.value;
    document.getElementById('axios-kelurahan').innerHTML = '<option value="">--</option>';
    document.getElementById('axios-kelurahan').disabled = true;
    if (!id) return;
    await axiosLoadOptions('/wilayah/kelurahan/' + id, 'axios-kelurahan', 'Pilih Kelurahan');
});

document.getElementById('axios-kelurahan').addEventListener('change', function () {
    const kelurahan = this.options[this.selectedIndex]?.text;
    const kecamatan = document.getElementById('axios-kecamatan').options[document.getElementById('axios-kecamatan').selectedIndex]?.text;
    const kota = document.getElementById('axios-kota').options[document.getElementById('axios-kota').selectedIndex]?.text;
    const provinsi = document.getElementById('axios-provinsi').options[document.getElementById('axios-provinsi').selectedIndex]?.text;
    if (this.value) {
        const el = document.getElementById('axios-hasil');
        el.classList.remove('d-none');
        el.innerHTML = `<strong>Hasil:</strong> ${kelurahan}, ${kecamatan}, ${kota}, ${provinsi}`;
    }
});
</script>
@endsection
