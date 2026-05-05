<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4 portrait; margin: 25mm 30mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; color: #333; }
        .kop { text-align: center; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 16px; }
        .kop-nama { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .kop-alamat { font-size: 9pt; color: #555; margin-top: 3px; }
        .nomor { margin-bottom: 16px; font-size: 11pt; }
        .nomor table td { padding: 2px 4px; vertical-align: top; }
        .perihal-label { font-weight: bold; }
        .pembuka { margin-bottom: 14px; line-height: 1.8; }
        .isi { line-height: 1.8; margin-bottom: 14px; }
        .acara { width: 100%; border-collapse: collapse; margin: 10px 0 14px; }
        .acara td { padding: 4px 8px; border: 1px solid #ccc; font-size: 11pt; }
        .acara td:first-child { width: 35%; font-weight: bold; }
        .penutup { line-height: 1.8; margin-bottom: 20px; }
        .ttd { text-align: right; }
        .ttd-tempat { margin-bottom: 4px; }
        .ttd-jabatan { font-weight: bold; }
        .ttd-nama { font-weight: bold; margin-top: 50px; text-decoration: underline; }
        .ttd-nip { font-size: 10pt; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="kop-nama">Universitas Teknologi Nusantara</div>
        <div class="kop-nama" style="font-size:12pt;">Fakultas Ilmu Komputer</div>
        <div class="kop-alamat">Jl. Teknologi No. 1, Jakarta Selatan | Telp. (021) 123-4567 | Email: info@utn.ac.id</div>
    </div>

    <div class="nomor">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>001/FIK/UTN/V/2026</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>–</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><span class="perihal-label">Undangan Seminar Teknologi Informasi</span></td>
            </tr>
        </table>
    </div>

    <div class="pembuka">
        Kepada Yth.<br>
        <strong>Bapak/Ibu Peserta</strong><br>
        di Tempat
    </div>

    <div class="isi">
        Dengan hormat,<br><br>
        Sehubungan dengan akan diadakannya kegiatan Seminar Teknologi Informasi tahun 2026,
        bersama ini kami mengundang Bapak/Ibu untuk hadir pada acara tersebut dengan rincian sebagai berikut:
    </div>

    <table class="acara">
        <tr><td>Hari, Tanggal</td><td>Senin, 10 Mei 2026</td></tr>
        <tr><td>Pukul</td><td>08.00 WIB – selesai</td></tr>
        <tr><td>Tempat</td><td>Aula Gedung A, Lantai 3</td></tr>
        <tr><td>Tema</td><td>Transformasi Digital di Era Industry 5.0</td></tr>
        <tr><td>Dress Code</td><td>Formal / Batik</td></tr>
    </table>

    <div class="penutup">
        Mengingat pentingnya acara tersebut, kami sangat mengharapkan kehadiran Bapak/Ibu tepat waktu.
        Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.
    </div>

    <div class="ttd">
        <div class="ttd-tempat">Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</div>
        <div class="ttd-jabatan">Dekan Fakultas Ilmu Komputer</div>
        <div class="ttd-nama">Dr. Ahmad Fauzi, M.Kom</div>
        <div class="ttd-nip">NIP. 197501012005011001</div>
    </div>
</body>
</html>
