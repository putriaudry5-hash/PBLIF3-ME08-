
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/detail-pesanan.css') }}">
</head>
<body>
    <main class="detail-container">
        <a href="{{ route('pelanggan.pembayaran.berhasil') }}" class="back-link">
            &larr; Kembali
        </a>

        <h1>Detail Pesanan</h1>

        <section class="order-info">
            <div>
                <span>Nomor Pesanan</span>
                <strong>{{ $pesanan['nomor'] }}</strong>
            </div>
            <div>
                <span>Waktu</span>
                <strong>{{ $pesanan['waktu'] }}</strong>
            </div>
            <div class="status-label">
                {{ $pesanan['status'] === 'Pembayaran Berhasil' ? 'Sedang Diproses' : $pesanan['status'] }}
            </div>
        </section>

        <section class="timeline">
            @php
                $tahapan = [
                    'Pembayaran Berhasil',
                    'Pesanan Diterima Tenant',
                    'Sedang Diproses',
                    'Siap Diambil',
                    'Selesai',
                ];
            @endphp

            @foreach ($tahapan as $index => $tahap)
                <div class="timeline-item">
                    <span class="timeline-dot {{ $index === 0 ? 'active' : '' }}"></span>
                    <span>{{ $tahap }}</span>
                </div>
            @endforeach
        </section>

        <div class="status-message">
            Pesanan anda sedang disiapkan oleh tenant. Mohon menunggu ya!
        </div>

            <div class="item-row total">
                <span>Total Pembayaran</span>
                <strong>Rp{{ number_format($pesanan['total'], 0, ',', '.') }}</strong>
            </div>
        </section>

        <a href="{{ route('pelanggan.dashboard') }}" class="selesai-button">
            Selesai
        </a>
    </main>
</body>
</html>
