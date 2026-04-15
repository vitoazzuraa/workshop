<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin:1mm 2mm 1mm 2mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }
        table {
            border-collapse: separate;
            border-spacing: 2mm 1mm;
        }
        td {
            width: 38mm;
            height: 22mm;
            border: 0.2mm solid #ccc;
            text-align: center;
            vertical-align: middle;
            box-sizing: border-box;
            padding: 2mm;
        }
        .barcode div {
            margin: 0 auto;
        }
    </style>
</head>
<body>

<table>
    @for($row = 0; $row < 8; $row++)
        <tr>
            @for($col = 0; $col < 5; $col++)
                @php
                    $index = ($row * 5) + $col;
                    $label = $labels[$index] ?? null;
                @endphp
                <td>
                    @if($label)
                        <div class="barcode">
                            @php
                                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                            @endphp
                            {!! $generator->getBarcode($label->id_barang, $generator::TYPE_CODE_128, 1, 20) !!}
                        </div>
                        <div style="margin-top: 2px; font-weight: bold;">
                            {{ $label->id_barang }}
                        </div>
                        <span class="name">{{ $label->nama }}</span>
                        <br>
                        <span class="price">
                            Rp {{ number_format($label->harga, 0, ',', '.') }}
                        </span>
                    @endif
                </td>
            @endfor
        </tr>
    @endfor
</table>

</body>
</html>