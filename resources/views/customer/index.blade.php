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
                            <label class="font-weight-bold">Pilih Kantin / Penyedia:</label>
                            <select name="idvendor" class="form-control form-control-lg border-primary"
                                onchange="this.form.submit()">
                                <option value="">-- Semua Kantin --</option>
                                @foreach ($vendor as $toko)
                                    <option value="{{ $toko->idvendor }}"
                                        {{ request('idvendor') == $toko->idvendor ? 'selected' : '' }}>
                                        {{ $toko->nama_vendor }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <div class="row">
                            @forelse($menu as $item)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
                                    <div class="card h-100 border-0 shadow-sm" style="background: #fbfbfb;">
                                        <div class="text-center p-2">
                                            @if ($item->path_gambar)
                                                <img src="{{ asset('storage/' . $item->path_gambar) }}"
                                                    class="img-fluid rounded"
                                                    style="height: 150px; width: 100%; object-fit: cover;"
                                                    alt="{{ $item->nama_menu }}">
                                            @else
                                                <img src="{{ asset('assets/images/no-image.jpg') }}"
                                                    class="img-fluid rounded"
                                                    style="height: 150px; width: 100%; object-fit: cover;" alt="No Image">
                                            @endif
                                        </div>
                                        <div class="card-body p-3">
                                            <h5 class="font-weight-bold text-dark mb-1">{{ $item->nama_menu }}</h5>
                                            <h4 class="text-primary mt-2 mb-3 font-weight-bold">Rp
                                                {{ number_format($item->harga, 0, ',', '.') }}</h4>

                                            <button class="btn btn-outline-primary btn-block btn-sm"
                                                onclick="addToCart({{ $item->idmenu }}, '{{ $item->nama_menu }}', {{ $item->harga }}, {{ $item->idvendor }})">
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
                        <input type="hidden" name="idvendor" id="vendor-input">

                        <button type="button" id="pay-button" onclick="submitCheckout()"
                            class="btn btn-gradient-success btn-lg btn-block font-weight-bold shadow-sm">
                            BAYAR SEKARANG <i class="mdi mdi-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <script>
        let cart = [];

        function addToCart(id, nama, harga, vendorId) {
            if (cart.length > 0 && cart[0].vendorId !== vendorId) {
                alert("Maaf, Anda tidak bisa mencampur pesanan dari kantin yang berbeda. Selesaikan dulu pesanan yang ada.");
                return;
            }

            let existing = cart.find(i => i.idmenu === id);
            if (existing) {
                existing.jumlah++;
            } else {
                cart.push({
                    idmenu: id,
                    nama: nama,
                    harga: harga,
                    jumlah: 1,
                    vendorId: vendorId
                });
            }
            renderCart();
        }

        function removeFromCart(id) {
            let index = cart.findIndex(i => i.idmenu === id);
            
            if (index !== -1) {
                if (cart[index].jumlah > 1) {
                    cart[index].jumlah--;
                } else {
                    cart.splice(index, 1);
                }
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
                document.getElementById('cart-data-input').value = "";
                document.getElementById('vendor-input').value = "";
                return;
            }

            list.innerHTML = '';
            cart.forEach((item) => {
                total += item.harga * item.jumlah;
                list.innerHTML += `
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <div style="width: 60%;">
                        <h6 class="mb-0 font-weight-bold text-truncate">${item.nama}</h6>
                        <small class="text-muted">Rp ${item.harga.toLocaleString('id-ID')}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-danger px-2 py-0" onclick="removeFromCart(${item.idmenu})">-</button>
                        <span class="mx-2 font-weight-bold">${item.jumlah}</span>
                        <button class="btn btn-sm btn-outline-success px-2 py-0" onclick="addToCart(${item.idmenu}, '${item.nama}', ${item.harga}, ${item.vendorId})">+</button>
                    </div>
                </div>`;
            });

            totalDisplay.innerText = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('cart-data-input').value = JSON.stringify(cart);
            document.getElementById('vendor-input').value = cart[0].vendorId;
        }

        function submitCheckout() {
            if (cart.length === 0) {
                alert("Keranjang masih kosong!");
                return;
            }

            let payButton = document.getElementById('pay-button');
            payButton.disabled = true;
            payButton.innerText = "Memproses...";

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
                                window.location.href = "{{ url('/checkout/success') }}/" + result.order_id;
                            },
                            onPending: function(result) {
                                window.location.href = "{{ url('/checkout/success') }}/" + result.order_id;
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
                        alert(data.message || "Gagal membuat pesanan.");
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