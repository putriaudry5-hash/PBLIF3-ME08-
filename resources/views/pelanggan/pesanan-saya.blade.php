<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/pesanan-saya.css') }}">
</head>
<body>
    <main class="container">
        <a href="{{ route('pelanggan.dashboard') }}" class="back-link">
            &larr; Kembali ke Beranda
        </a>

        <h1>Pesanan Saya</h1>
        <p class="subtitle">
            Pantau rincian dan perkembangan pesanan kamu di KantinKita.
        </p>

        @forelse ($pesanans as $pesanan)
            <article class="order-card">
                <div class="order-header">
                    <div>
                        <h2>Detail Pesanan</h2>
                        <p class="order-number">
                            {{ $pesanan->kode_pesanan }}
                        </p>
                        <p class="tenant-name">
                            {{ $pesanan->tenant->nama_tenant
                                ?? $pesanan->tenant->nama
                                ?? 'Tenant KantinKita' }}
                        </p>
                    </div>

                    <span class="status-badge">
                        {{ ucfirst(str_replace('_', ' ', $pesanan->status_pesanan)) }}
                    </span>
                </div>

                <section class="timeline">
                    @php
                        $tahapan = [
                            'menunggu' => 0,
                            'diproses' => 2,
                            'siap_diambil' => 3,
                            'selesai' => 4,
                        ];

                        $status = $pesanan->status_pesanan;
                        $aktifSampai = $tahapan[$status] ?? 0;
                    @endphp

                    @foreach ([
                        'Pembayaran Berhasil',
                        'Pesanan Diterima Tenant',
                        'Sedang Diproses',
                        'Siap Diambil',
                        'Selesai'
                    ] as $index => $tahap)
                        <div class="timeline-item">
                            <span class="timeline-dot
                                {{ $index <= $aktifSampai ? 'active' : '' }}">
                                @if ($index <= $aktifSampai)
                                    ✓
                                @endif
                            </span>

                            <span class="{{ $index <= $aktifSampai ? 'active-text' : '' }}">
                                {{ $tahap }}
                            </span>
                        </div>
                    @endforeach
                </section>

                <section class="order-details">
                    <h3>Rincian Pesanan</h3>

                    @forelse ($pesanan->details as $detail)
                        <div class="item-row">
                            <span>
                                {{ $detail->nama_item }} × {{ $detail->jumlah }}
                            </span>

                            <strong>
                                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                            </strong>
                        </div>
                    @empty
                        <p>Rincian menu belum tersedia.</p>
                    @endforelse

                    <div class="item-row total-row">
                        <strong>Total Pembayaran</strong>
                        <strong>
                            Rp{{ number_format($pesanan->total, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="payment-info">
                        Status pembayaran:
                        <strong>
                            {{ ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran)) }}
                        </strong>
                    </div>
                </section>
            </article>
        @empty
            <section class="empty-state">
                <h2>Belum Ada Pesanan</h2>
                <p>Pesanan yang kamu buat akan muncul di halaman ini.</p>

                <a href="{{ route('pelanggan.dashboard') }}" class="primary-button">
                    Mulai Pesan Menu
                </a>
            </section>
        @endforelse
    </main>
</body>
</html>