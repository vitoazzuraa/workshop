<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4 landscape; margin: 20mm 25mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; }
        .border-outer { border: 8px double #4a2c9e; padding: 18px; height: 170mm; position: relative; }
        .border-inner { border: 2px solid #4a2c9e; padding: 20px; height: 100%; text-align: center; }
        .header-logo { font-size: 13pt; font-weight: bold; color: #4a2c9e; letter-spacing: 3px; text-transform: uppercase; border-bottom: 2px solid #4a2c9e; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 26pt; font-weight: bold; color: #4a2c9e; text-transform: uppercase; letter-spacing: 8px; margin: 8px 0; }
        .subtitle { font-size: 11pt; color: #666; margin-bottom: 18px; }
        .recipient-label { font-size: 10pt; color: #666; margin-bottom: 4px; }
        .recipient-name { font-size: 20pt; font-weight: bold; color: #333; font-style: italic; border-bottom: 2px solid #4a2c9e; padding-bottom: 4px; display: inline-block; min-width: 200px; margin-bottom: 16px; }
        .body-text { font-size: 10pt; color: #555; line-height: 1.8; margin-bottom: 18px; }
        .sig-table { width: 70%; margin: 0 auto; }
        .sig-table td { text-align: center; padding: 0 20px; }
        .sig-line { border-bottom: 1px solid #333; width: 130px; margin: 38px auto 4px; }
        .sig-name { font-size: 9pt; font-weight: bold; color: #333; }
        .sig-title { font-size: 8pt; color: #666; }
        .date { font-size: 9pt; color: #666; margin-top: 10px; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%) rotate(-30deg); font-size: 60pt; color: rgba(111,66,193,0.05); font-weight: bold; white-space: nowrap; }
    </style>
</head>
<body>
<div class="border-outer">
    <div class="watermark">SERTIFIKAT</div>
    <div class="border-inner">
        <div class="header-logo">Universitas Teknologi Nusantara</div>
        <div class="title">Sertifikat</div>
        <div class="subtitle">Penghargaan Atas Partisipasi</div>
        <div class="recipient-label">Diberikan Kepada</div>
        <div class="recipient-name">Muhammad Budi Santoso</div>
        <div class="body-text">
            Atas partisipasi aktif dan kontribusi yang luar biasa dalam kegiatan<br>
            <strong>Pelatihan Sistem Informasi Manajemen</strong><br>
            yang diselenggarakan pada tanggal 1 – 5 Mei 2026
        </div>
        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-name">Dr. Ahmad Fauzi, M.Kom</div>
                    <div class="sig-title">Dekan Fakultas Ilmu Komputer</div>
                </td>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-name">Prof. Rina Kartika, Ph.D</div>
                    <div class="sig-title">Rektor</div>
                </td>
            </tr>
        </table>
        <div class="date">Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
    </div>
</div>
</body>
</html>
