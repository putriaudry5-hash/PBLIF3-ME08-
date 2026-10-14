
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/pembayaran-berhasil.css') }}">
</head>
<body>
    <main class="success-container">
        <div class="check-icon">✓</div>

        <h1>Pembayaran Berhasil!</h1>
        <p>Terima kasih, pesananmu sudah kami terima.</p>

        <section class="summary">
            <div><span>Nomor Pesanan</span><strong>{{ $pesanan['nomor'] }}</strong></div>
            <div><span>Tenant</span><strong>{{ $pesanan['tenant_nama'] }}</strong></div>
            <div><span>Total</span><strong>Rp{{ number_format($pesanan['total'], 0, ',', '.') }}</strong></div>
            <div><span>Metode</span><strong>{{ strtoupper($pesanan['metode']) }}</strong></div>
            <div><span>Waktu</span><strong>{{ $pesanan['waktu'] }}</strong></div>
        </section>

        <a class="button primary"
           href="{{ route('pelanggan.detail-pesanan', ['nomor' => $pesanan['nomor']]) }}">
            Lihat Detail Pemesanan
        </a>

        <a class="button secondary" href="{{ route('pelanggan.dashboard') }}">
            Kembali ke Beranda
        </a>
    </main>
</body>
</html>
