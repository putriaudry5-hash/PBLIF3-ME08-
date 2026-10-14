
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Checkout - KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <script src="{{ asset('js/checkout.js') }}" defer></script>
</head>
<body>
    <div class="container">
        <h1>Checkout Pesanan</h1>
        <p>Periksa kembali menu yang akan kamu pesan.</p>

        @forelse ($items as $item)
            <div class="item">
                <div class="item-info">
                    <strong>{{ $item->menu_nama }}</strong><br>
                    <small>{{ $item->tenant_nama }}</small><br>
                    {{ $item->jumlah }} ×
                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                </div>

                <strong>
                    Rp{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                </strong>
            </div>
        @empty
            <p>Keranjang kamu masih kosong.</p>
        @endforelse

        <div class="total">
            Total: Rp{{ number_format($total, 0, ',', '.') }}
        </div>

        @if ($items->isNotEmpty())
            <h2>Metode Pembayaran</h2>

            @if ($errors->any())
                <p class="error-message">
                    {{ $errors->first() }}
                </p>
            @endif

            <form
                id="form-pembayaran"
                action="{{ route('pelanggan.pembayaran') }}"
                method="POST"
            >
                @csrf

                <select
                    name="metode"
                    id="metode-pembayaran"
                    required
                >
                    <option value="">Pilih metode pembayaran</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="ewallet">E-Wallet</option>
                </select>

                <button type="submit" id="tombol-pembayaran">
                    Lanjutkan Pembayaran
                </button>
            </form>
        @endif

        <a
            class="kembali"
            href="{{ route('pelanggan.keranjang') }}"
        >
            &larr; Kembali ke Keranjang
        </a>
    </div>
</body>
</html>