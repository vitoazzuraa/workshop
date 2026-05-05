<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: 210mm 170mm; margin: 5mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; }
        table.label-grid {
            width: 200mm;
            border-collapse: separate;
            border-spacing: 1mm;
            table-layout: fixed;
        }
        td.label-cell {
            width: 38mm;
            height: 18mm;
            border: 0.4pt dashed #aaa;
            padding: 0.5mm 1mm;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
        }
        td.label-cell.empty { border: 0.4pt dashed #ccc; }
        .label-nama { font-size: 6pt; font-weight: bold; white-space: nowrap; overflow: hidden; }
        .label-harga { font-size: 8pt; font-weight: bold; color: #c00; }
        .label-id { font-size: 5pt; color: #555; letter-spacing: 0.5px; }
    </style>
</head>
<body>
<?php
$cols   = 5;
$rows   = 8;
$total  = $cols * $rows;
// offset = jumlah sel kosong di awal
$offset = ($startY - 1) * $cols + ($startX - 1);
// buat array sel: null = kosong, array = data barang
$cells = array_fill(0, $total, null);
$itemArr = $items->toArray();
for ($i = 0; $i < count($itemArr) && ($i + $offset) < $total; $i++) {
    $cells[$i + $offset] = $itemArr[$i];
}
// bagi ke baris-baris
$pages = array_chunk($cells, $cols * $rows);
?>
@foreach($pages as $page)
<table class="label-grid">
    @foreach(array_chunk($page, $cols) as $row)
    <tr>
        @foreach($row as $cell)
        <td class="label-cell {{ is_null($cell) ? 'empty' : '' }}">
            @if(!is_null($cell))
            <div class="label-nama">{{ $cell['nama'] }}</div>
            <div class="label-barcode">
                <img src="data:image/png;base64,{{ $cell['barcode'] }}" style="height:7mm;max-width:34mm;display:block;margin:0 auto;">
            </div>
            <div class="label-id">{{ $cell['id_barang'] }}</div>
            <div class="label-harga">Rp {{ number_format($cell['harga'], 0, ',', '.') }}</div>
            @endif
        </td>
        @endforeach
    </tr>
    @endforeach
</table>
@endforeach
</body>
</html>
