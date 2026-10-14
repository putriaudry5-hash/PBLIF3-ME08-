<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pembayaran - KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
</head>
<body>
    <main class="container">
        <h1>Pembayaran Pesanan</h1>
        <p>Periksa kembali pesanan dan metode pembayaran kamu.</p>

        <section class="ringkasan">
            <h2>Ringkasan Pesanan</h2>

            @forelse ($items as $item)
                <div class="item">
                    <div>
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
                <p>Belum ada pesanan di keranjang.</p>
            @endforelse

            <div class="total">
                Total: Rp{{ number_format($total, 0, ',', '.') }}
            </div>
        </section>

        <h2>Metode Pembayaran</h2>

        <div class="metode">
            @if ($metode === 'qris')
                <strong>QRIS</strong>
            @elseif ($metode === 'transfer')
                <strong>Transfer Bank</strong>
            @elseif ($metode === 'ewallet')
                <strong>E-Wallet</strong>
            @else
                <strong>Metode pembayaran belum dipilih</strong>
            @endif
        </div>

        <p class="catatan">
            Halaman ini masih berupa simulasi pembayaran.
            Pembayaran belum diproses secara nyata karena payment gateway
            belum diintegrasikan.
        </p>

        {{-- Form konfirmasi pembayaran --}}
        <form
            action="{{ route('pelanggan.pembayaran.konfirmasi') }}"
            method="POST"
        >
            @csrf

            <input type="hidden" name="metode" value="{{ $metode }}">

            <button type="submit" id="konfirmasi-pembayaran">
                Konfirmasi Pembayaran
            </button>
        </form>

        <a class="kembali" href="{{ route('pelanggan.keranjang') }}">
            Kembali ke Keranjang
        </a>
    </main>
</body>
</html>