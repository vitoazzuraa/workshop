<style>
    body { font-family: sans-serif; text-align: center; }
    .border { border: 10px double #666; padding: 50px; }
    h1 { font-size: 50px; margin-bottom: 0; }
    .nama { font-size: 40px; color: purple; text-decoration: underline; }
</style>

<div class="border">
    <h3>SERTIFIKAT</h3>
    <p>Nomor: {{ $nomor }}</p>
    <p>Diberikan kepada:</p>
    <h1 class="nama">{{ $nama }}</h1>
    <p>Atas Partisipasinya Sebagai:</p>
    <h2>{{ $peran }}</h2>
</div>
