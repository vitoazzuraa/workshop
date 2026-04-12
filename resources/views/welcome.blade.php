<div class="text-center mt-5">
    <a href="{{ route('katalog.index') }}" class="btn btn-lg btn-primary">Pesan Makanan (Guest)</a>

    @guest
        <a href="{{ route('login') }}" class="btn btn-lg btn-outline-secondary">Login User (Cek Pesanan Masuk)</a>
    @else
        <a href="{{ route('home') }}" class="btn btn-lg btn-success">Ke Dashboard Workshop</a>
    @endguest
</div>
