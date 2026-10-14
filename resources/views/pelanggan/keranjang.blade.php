<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Keranjang Belanja - KantinKita</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/keranjang.css') }}"
    >
</head>
<body>

<div class="page">

    <a href="{{ route('pelanggan.dashboard') }}" class="back-link">
        &larr; Kembali ke daftar tenant
    </a>

    @if (session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
    @endif

    <section class="cart-card">

        <header class="cart-header">
            <div>
                <h1>Keranjang Belanja</h1>
                <p>Periksa pesananmu sebelum melanjutkan pembayaran.</p>
            </div>

            @if ($items->isNotEmpty())
                <form
                    action="{{ route('pelanggan.keranjang.kosongkan') }}"
                    method="POST"
                    class="delete-all-form"
                    data-confirm="Yakin ingin menghapus semua menu dari keranjang?"
                >
                    @csrf
                    <button type="submit" class="btn-delete-all">
                        Hapus Semua
                    </button>
                </form>
            @endif
        </header>

        <div class="cart-content">

            @if ($items->isEmpty())

                <div class="empty-cart">
                    <div class="empty-icon">🛒</div>
                    <h2>Keranjang Masih Kosong</h2>
                    <p>Yuk, pilih makanan dan minuman favoritmu!</p>

                    <a
                        href="{{ route('pelanggan.dashboard') }}"
                        class="btn-add-menu"
                    >
                        + Tambah Menu
                    </a>
                </div>

            @else

                <h2 class="tenant-name">
                    <span class="tenant-dot"></span>
                    {{ $items->first()->tenant_nama }}
                </h2>

                <div class="cart-items">

                    @foreach ($items as $item)
                        <article class="cart-item">

                            <div class="item-image">
                                <img
                                    src="{{ $item->gambar }}"
                                    alt="{{ $item->menu_nama }}"
                                    loading="lazy"
                                >
                            </div>

                            <div class="item-info">
                                <h3>{{ $item->menu_nama }}</h3>
                                <p>Harga satuan</p>

                                <div class="item-price">
                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                </div>

                                <p class="item-subtotal-mobile">
                                    Subtotal:
                                    Rp{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="item-controls">

                                <div class="quantity-control">

                                    <form
                                        action="{{ route('pelanggan.keranjang.jumlah', $item->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <input type="hidden" name="aksi" value="kurang">

                                        <button
                                            type="submit"
                                            aria-label="Kurangi jumlah"
                                        >−</button>
                                    </form>

                                    <span>{{ $item->jumlah }}</span>

                                    <form
                                        action="{{ route('pelanggan.keranjang.jumlah', $item->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <input type="hidden" name="aksi" value="tambah">

                                        <button
                                            type="submit"
                                            aria-label="Tambah jumlah"
                                        >+</button>
                                    </form>

                                </div>

                                <div class="item-subtotal">
                                    Rp{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                </div>

                                <form
                                    action="{{ route('pelanggan.keranjang.hapus', $item->id) }}"
                                    method="POST"
                                    class="delete-item-form"
                                    data-confirm="Hapus menu ini dari keranjang?"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-remove">
                                        Hapus menu
                                    </button>
                                </form>

                            </div>
                        </article>
                    @endforeach

                </div>

                <div class="add-menu-wrapper">
                    <a href="{{ route('pelanggan.tenant.menu', ['tenant' => $items->first()->tenant_id]) }}"
                       class="tambah-menu-lain">
                        <span>+</span> Tambah Menu Lain
                        </a>
                </div>

                <div class="note-section">
                    <label for="catatan">
                        Catatan untuk Tenant (Opsional)
                    </label>

                    <textarea
                        id="catatan"
                        name="catatan"
                        placeholder="Contoh: sambal dipisah, tidak pakai bawang..."
                    ></textarea>
                </div>

                <div class="summary">

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong>
                            Rp{{ number_format($total, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Biaya Layanan</span>
                        <strong>Rp0</strong>
                    </div>

                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <strong>
                            Rp{{ number_format($total, 0, ',', '.') }}
                        </strong>
                    </div>

                </div>

                <div class="checkout-wrapper">
                    <a
                        href="{{ route('pelanggan.checkout') }}"
                        class="btn-checkout"
                    >
                        Lanjut ke Pembayaran &rarr;
                    </a>
                </div>

            @endif

        </div>
    </section>

</div>


<script src="{{ asset('js/keranjang.js') }}" defer></script>

</body>
</html>