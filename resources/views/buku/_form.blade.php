@php $p = $prefix ?? ''; @endphp

<div class="mb-3">
    <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
    <input type="text" name="kode" id="{{ $p ? $p.'-kode' : 'kode' }}"
           class="form-control" maxlength="20" required>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
    <input type="text" name="judul" id="{{ $p ? $p.'-judul' : 'judul' }}"
           class="form-control" maxlength="500" required>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Pengarang <span class="text-danger">*</span></label>
    <input type="text" name="pengarang" id="{{ $p ? $p.'-pengarang' : 'pengarang' }}"
           class="form-control" maxlength="200" required>
</div>
<div class="mb-3">
    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
    <select name="idkategori" id="{{ $p ? $p.'-idkategori' : 'idkategori' }}"
            class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategori as $k)
            <option value="{{ $k->idkategori }}">{{ $k->nama_kategori }}</option>
        @endforeach
    </select>
</div>
