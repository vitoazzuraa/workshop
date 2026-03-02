<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 1mm 2mm 1mm 2mm;
        }

        body {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
        }

        table {
            border-collapse: separate;
            border-spacing: 2mm 1mm;
            width: 198mm; /* 190 + 8 */
        }

        td {
            width: 38mm;
            height: 18mm;
            border: 0.2mm solid #ccc;
            text-align: center;
            vertical-align: middle;
            padding: 1mm;
            box-sizing: border-box;
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
                    $label = $labels[$index];
                @endphp
                <td>
                    @if($label)
                        <span class="name">{{ $label->nama }}</span>
                        <span class="price">
                            Rp {{ number_format($label->harga, 0, ',', '.') }}
                        </span>
                        <span class="sku">{{ $label->id_barang }}</span>
                    @endif
                </td>
            @endfor
        </tr>
    @endfor
</table>

</body>
</html>
