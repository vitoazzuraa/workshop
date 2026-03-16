@extends('layouts.master')
@section('title', 'Pencarian Wilayah Indonesia')

@section('content')

<h2>Pencarian Wilayah</h2>

{{-- Tombol Toggle --}}
<div>
    <button id="btn-jquery" onclick="setMode('jquery')"><strong>Ajax jQuery</strong></button>
    <button id="btn-axios" onclick="setMode('axios')">Axios</button>
</div>

<p id="mode-label">Mode aktif: <strong>Ajax jQuery</strong></p>

<br>

<label>Provinsi</label><br>
<select id="sel-province">
    <option value="0">— Pilih Provinsi —</option>
    @foreach($provinces as $p)
    <option value="{{ $p->id }}">{{ $p->name }}</option>
    @endforeach
</select>

<br><br>

<label>Kabupaten/Kota</label><br>
<select id="sel-regency">
    <option value="0">— Pilih Kab/Kota —</option>
</select>

<br><br>

<label>Kecamatan</label><br>
<select id="sel-district">
    <option value="0">— Pilih Kecamatan —</option>
</select>

<br><br>

<label>Kelurahan/Desa</label><br>
<select id="sel-village">
    <option value="0">— Pilih Kel/Desa —</option>
</select>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

var activeMode = 'jquery';

function setMode(mode) {
    activeMode = mode;
    $('#sel-province').val('0');
    $('#sel-regency, #sel-district, #sel-village')
        .html('<option value="0">— Pilih —</option>');
    $('#mode-label').html(
        'Mode aktif: <strong>' + (mode === 'jquery' ? 'Ajax jQuery' : 'Axios') + '</strong>'
    );
    $('#btn-jquery').css('font-weight', mode === 'jquery' ? 'bold' : 'normal');
    $('#btn-axios').css('font-weight',  mode === 'axios'  ? 'bold' : 'normal');
}

// ===== AJAX JQUERY =====
function ajaxGetRegency(id) {
    $.ajax({
        url: "{{ route('wilayah.regency') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}", province_id: id },
        success: function(res) {
            $.each(res.data, function(i, item) {
                $('#sel-regency').append(
                    '<option value="' + item.id + '">' + item.name + '</option>'
                );
            });
        },
        error: function(xhr) { console.log(xhr); }
    });
}
function ajaxGetDistrict(id) {
    $.ajax({
        url: "{{ route('wilayah.district') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}", regency_id: id },
        success: function(res) {
            $.each(res.data, function(i, item) {
                $('#sel-district').append(
                    '<option value="' + item.id + '">' + item.name + '</option>'
                );
            });
        },
        error: function(xhr) { console.log(xhr); }
    });
}
function ajaxGetVillage(id) {
    $.ajax({
        url: "{{ route('wilayah.village') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}", district_id: id },
        success: function(res) {
            $.each(res.data, function(i, item) {
                $('#sel-village').append(
                    '<option value="' + item.id + '">' + item.name + '</option>'
                );
            });
        },
        error: function(xhr) { console.log(xhr); }
    });
}

// ===== AXIOS =====
const token = "{{ csrf_token() }}";

function axiosGetRegency(id) {
    axios.post("{{ route('wilayah.regency') }}", {
        _token: token, province_id: id
    })
    .then(function(res) {
        res.data.data.forEach(function(item) {
            $('#sel-regency').append(
                '<option value="' + item.id + '">' + item.name + '</option>'
            );
        });
    })
    .catch(function(e) { console.log(e); });
}
function axiosGetDistrict(id) {
    axios.post("{{ route('wilayah.district') }}", {
        _token: token, regency_id: id
    })
    .then(function(res) {
        res.data.data.forEach(function(item) {
            $('#sel-district').append(
                '<option value="' + item.id + '">' + item.name + '</option>'
            );
        });
    })
    .catch(function(e) { console.log(e); });
}
function axiosGetVillage(id) {
    axios.post("{{ route('wilayah.village') }}", {
        _token: token, district_id: id
    })
    .then(function(res) {
        res.data.data.forEach(function(item) {
            $('#sel-village').append(
                '<option value="' + item.id + '">' + item.name + '</option>'
            );
        });
    })
    .catch(function(e) { console.log(e); });
}

$(document).ready(function() {

    $('#sel-province').change(function() {
        var id = $(this).val();
        $('#sel-regency, #sel-district, #sel-village')
            .html('<option value="0">— Pilih —</option>');
        if (id == 0) return;
        console.log('Mode aktif:', activeMode);
        if (activeMode === 'jquery') {
            ajaxGetRegency(id);
        } else {
            axiosGetRegency(id);
        }
    });

    $('#sel-regency').change(function() {
        var id = $(this).val();
        $('#sel-district, #sel-village')
            .html('<option value="0">— Pilih —</option>');
        if (id == 0) return;
        if (activeMode === 'jquery') {
            ajaxGetDistrict(id);
        } else {
            axiosGetDistrict(id);
        }
    });

    $('#sel-district').change(function() {
        var id = $(this).val();
        $('#sel-village').html('<option value="0">— Pilih —</option>');
        if (id == 0) return;
        if (activeMode === 'jquery') {
            ajaxGetVillage(id);
        } else {
            axiosGetVillage(id);
        }
    });

});
</script>
@endsection
