<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Dashboard Tenant | KantinKita</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/tenant.css') }}"
    >
</head>

<body>
@php
    $user = auth('internal')->user();
@endphp

<div class="admin-layout">

@include('tenant.partials.sidebar')

    <main class="admin-main">

        <header class="topbar">

            <div class="topbar-title">
                <h1>Dashboard Tenant</h1>

                <p>
                    Ringkasan transaksi
                    {{ $tenant->nama_tenant }} hari ini.
                </p>
            </div>

          @include('tenant.partials.profile-menu')

        </header>

        <section class="stat-grid">

            <div class="stat-card stat-blue">

                <div class="stat-icon">
                    <i data-lucide="receipt-text"></i>
                </div>

                <div>
                    <p>Transaksi Hari Ini</p>

                    <h2>
                        {{ $transaksiHariIni }}
                    </h2>

                    <small>
                        Transaksi offline
                    </small>
                </div>

            </div>

            <div class="stat-card stat-yellow">

                <div class="stat-icon">
                    <i data-lucide="clock-3"></i>
                </div>

                <div>
                    <p>Menunggu Pembayaran</p>

                    <h2>
                        {{ $menungguPembayaran }}
                    </h2>

                    <small>
                        Belum dibayar di Kasir
                    </small>
                </div>

            </div>

            <div class="stat-card stat-green">

                <div class="stat-icon">
                    <i data-lucide="circle-check"></i>
                </div>

                <div>
                    <p>Transaksi Lunas</p>

                    <h2>
                        {{ $transaksiLunas }}
                    </h2>

                    <small>
                        Sudah dibayar
                    </small>
                </div>

            </div>

            <div class="stat-card stat-orange">

                <div class="stat-icon">
                    <i data-lucide="wallet-cards"></i>
                </div>

                <div>
                    <p>Pendapatan Hari Ini</p>

                    <h2>
                        Rp {{ number_format(
                            $pendapatanHariIni,
                            0,
                            ',',
                            '.'
                        ) }}
                    </h2>

                    <small>
                        Transaksi lunas
                    </small>
                </div>

            </div>

        </section>

        <section class="tenant-action-panel">

            <div>

                <div class="tenant-action-icon">
                    <i data-lucide="plus"></i>
                </div>

                <div>
                    <h2>Buat Transaksi Offline</h2>

                    <p>
                        Catat pesanan pelanggan dan
                        berikan kode transaksi untuk
                        pembayaran di Kasir Utama.
                    </p>
                </div>

            </div>

            <a
                href="{{ route(
                    'tenant.transaksi.offline'
                ) }}"
                class="tenant-primary-button"
            >
                <i data-lucide="plus"></i>
                Buat Transaksi
            </a>

        </section>

        <section class="panel tenant-panel">

            <div class="panel-header">

                <div>
                    <h2>Transaksi Terbaru</h2>

                    <p>
                        Transaksi offline terbaru
                        dari tenant ini.
                    </p>
                </div>

                <a href="{{ route(
                    'tenant.transaksi.offline'
                ) }}">
                    Lihat Semua →
                </a>

            </div>

            <div class="tenant-table-wrapper">

                <table class="tenant-table">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse (
                            $transaksiTerbaru
                            as $transaksi
                        )

                            <tr>

                                <td>
                                    <strong>
                                        {{ $transaksi
                                            ->kode_transaksi }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $transaksi
                                        ->created_at
                                        ?->format('H:i') }}
                                </td>

                                <td>
                                    {{ $transaksi
                                        ->details
                                        ->sum('jumlah') }}
                                    item
                                </td>

                                <td>
                                    Rp {{ number_format(
                                        $transaksi->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </td>

                                <td>

                                    @if (
                                        $transaksi->status ===
                                        'lunas'
                                    )

                                        <span
                                            class="status-success"
                                        >
                                            Lunas
                                        </span>

                                    @else

                                        <span
                                            class="status-waiting"
                                        >
                                            Menunggu Bayar
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="tenant-empty-table"
                                >
                                    Belum ada transaksi.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
);
</script>

</body>
</html>