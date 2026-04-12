@extends('layouts.guest')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 mt-2">
        <div class="col-12 text-center">
            <h2 class="display-4 font-weight-bold">Menu Kantin</h2>
            <p class="text-muted">Pesan makanan favorit Anda tanpa pindah halaman.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7 col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('landing') }}" method="GET" class="mb-4">
                        <label class="font-weight-bold">Pilih Penyedia:</label>
                        <select name="user_id" class="form-control form-control-lg border-primary" onchange="this.form.submit()">
                            <option value="">-- Semua Penyedia --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </form>

                    <div class="row">
                        @forelse($menus as $m)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm" style="background: #fbfbfb;">
                                <div class="text-center p-2">
                                    @if($m->path_gambar)
                                        <img src="{{ asset('storage/' . $m->path_gambar) }}" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" alt="{{ $m->nama_menu }}">
                                    @else
                                        <img src="{{ asset('assets/images/no-image.jpg') }}" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" alt="No Image">
                                    @endif
                                </div>
                                <div class="card-body p-3">
                                    <h5 class="font-weight-bold text-dark mb-1">{{ $m->nama_menu }}</h5>
                                    <p class="small text-muted mb-3">Oleh: {{ $m->user->name }}</p>
                                    <h4 class="text-primary mb-3 font-weight-bold">Rp {{ number_format($m->harga) }}</h4>
                                    <button class="btn btn-outline-primary btn-block btn-sm"
                                            onclick="addToCart({{ $m->idmenu }}, '{{ $m->nama_menu }}', {{ $m->harga }}, {{ $m->id }})">
                                        <i class="mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="mdi mdi-food-off display-3 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada menu yang tersedia.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 col-md-12">
            <div class="card shadow p-4 sticky-top" style="top: 100px; z-index: 100;">
                <h5 class="font-weight-bold"><i class="mdi mdi-cart-outline text-primary"></i> Keranjang Belanja</h5>
                <hr>
                <div id="cart-list" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-center text-muted">Keranjang kosong</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total:</h5>
                    <h4 class="text-success font-weight-bold mb-0" id="cart-total">Rp 0</h4>
                </div>

                <form id="checkout-form">
                    @csrf
                    <input type="hidden" name="cart_items" id="cart-data-input">
                    <input type="hidden" name="id_user_penyedia" id="user-penyedia-input">
                    <button type="button" id="pay-button" onclick="submitCheckout()" class="btn btn-gradient-success btn-lg btn-block font-weight-bold shadow-sm">
                        BAYAR SEKARANG <i class="mdi mdi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    let cart = [];

    function addToCart(id, nama, harga, userId) {
        if (cart.length > 0 && cart[0].userId !== userId) {
            alert("Maaf, Anda tidak bisa mencampur pesanan dari penyedia yang berbeda.");
            return;
        }

        let existing = cart.find(i => i.idmenu === id);
        if (existing) {
            existing.jumlah++;
        } else {
            cart.push({ idmenu: id, nama: nama, harga: harga, jumlah: 1, userId: userId });
        }
        renderCart();
    }

    function renderCart() {
        let list = document.getElementById('cart-list');
        let totalDisplay = document.getElementById('cart-total');
        let total = 0;

        if (cart.length === 0) {
            list.innerHTML = '<p class="text-center text-muted">Keranjang kosong</p>';
            totalDisplay.innerText = "Rp 0";
            return;
        }

        list.innerHTML = '';
        cart.forEach((item) => {
            total += item.harga * item.jumlah;
            list.innerHTML += `
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <div>
                        <h6 class="mb-0 font-weight-bold">${item.nama}</h6>
                        <small class="text-muted">${item.jumlah} x Rp ${item.harga.toLocaleString()}</small>
                    </div>
                    <span class="font-weight-bold text-dark">Rp ${(item.harga * item.jumlah).toLocaleString()}</span>
                </div>`;
        });

        totalDisplay.innerText = "Rp " + total.toLocaleString();
        document.getElementById('cart-data-input').value = JSON.stringify(cart);
        document.getElementById('user-penyedia-input').value = cart[0].userId;
    }

    function submitCheckout() {
        if (cart.length === 0) {
            alert("Keranjang masih kosong!");
            return;
        }

        let payButton = document.getElementById('pay-button');
        payButton.disabled = true;
        payButton.innerText = "Processing...";

        let formData = new FormData(document.getElementById('checkout-form'));

        fetch("{{ route('checkout.store') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        alert("Pembayaran Berhasil!");
                        location.reload();
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda.");
                        location.reload();
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal, silakan coba lagi.");
                        payButton.disabled = false;
                        payButton.innerHTML = 'BAYAR SEKARANG <i class="mdi mdi-arrow-right"></i>';
                    },
                    onClose: function() {
                        alert('Anda menutup popup sebelum menyelesaikan pembayaran.');
                        payButton.disabled = false;
                        payButton.innerHTML = 'BAYAR SEKARANG <i class="mdi mdi-arrow-right"></i>';
                    }
                });
            } else {
                alert("Gagal membuat pesanan.");
                payButton.disabled = false;
                payButton.innerHTML = 'BAYAR SEKARANG <i class="mdi mdi-arrow-right"></i>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan sistem.");
            payButton.disabled = false;
            payButton.innerHTML = 'BAYAR SEKARANG <i class="mdi mdi-arrow-right"></i>';
        });
    }
</script>
@endsection
