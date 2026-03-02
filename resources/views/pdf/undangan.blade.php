<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Rapat</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 1.5cm 2.5cm;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .kop h2 { margin: 0; font-size: 14pt; }
        .kop h1 { margin: 0; font-size: 16pt; }
        .kop p { margin: 2px 0; font-size: 10pt; }

        .isi { text-align: justify; }
        .tabel-data { margin: 15px 0 15px 30px; }
        .tabel-data td { padding: 3px 5px; }

        .ttd-table {
            width: 100%;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="kop">
            <h2>UNIVERSITAS AIRLANGGA</h2>
            <h1>FAKULTAS VOKASI</h1>
            <p>Kampus B UNAIR, Jl. Dharmawangsa Dalam No.33-35, Surabaya</p>
            <p>Laman: https://vokasi.unair.ac.id | Surel: info@vokasi.unair.ac.id</p>
        </div>

        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td>Nomor : {{ $nomor }}</td>
                <td align="right">Surabaya, {{ $tanggal }}</td>
            </tr>
            <tr>
                <td>Perihal : <strong>{{ $agenda }}</strong></td>
                <td></td>
            </tr>
        </table>

        <p>Yth. Bapak/Ibu Dosen<br>Fakultas Vokasi UNAIR<br>Di Tempat</p>

        <div class="isi">
            <p>Dengan hormat,</p>
            <p>Sehubungan dengan acara pemilihan Ketua Himpunan Mahasiswa Teknik Informatika 2026</p>

            <p>Pertemuan tersebut akan dilaksanakan pada:</p>
            <table class="tabel-data">
                <tr>
                    <td width="100">Hari, Tanggal</td>
                    <td>: Sabtu, 28 Februari 2026</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>: 09.00 WIB - Selesai</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>: Ruang Rapat Fakultas Vokasi, Kampus B UNAIR</td>
                </tr>
            </table>

            <p>Mengingat pentingnya koordinasi ini untuk kelancaran program kerja kita bersama, kehadiran Bapak/Ibu sangat diharapkan. Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.</p>
        </div>

        <table class="ttd-table">
            <tr>
                <td width="60%"></td>
                <td align="center">
                    Hormat kami,<br>
                    <strong>Ketua Pelaksana</strong>
                    <br><br><br><br>
                    <strong><u>Christianus Primavito</u></strong><br>
                    Fakultas Vokasi Unair
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
