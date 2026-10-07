<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi | KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>

<body>
<div class="admin-layout">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-mark">K</div>
            <div>
                <h2>Kantin<span>Kita</span></h2>
                <small>Kasir Utama / Admin</small>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.tenant') }}">
                <i data-lucide="store"></i>
                <span>Data Tenant</span>
            </a>

            <a href="{{ route('admin.pelanggan') }}">
                <i data-lucide="users"></i>
                <span>Data Pelanggan</span>
            </a>

            <a href="{{ route('admin.transaksi.offline') }}">
                <i data-lucide="receipt-text"></i>
                <span>Transaksi Offline</span>
            </a>

            <a href="{{ route('admin.transaksi.online') }}">
                <i data-lucide="credit-card"></i>
                <span>Transaksi Online</span>
            </a>

            <a href="{{ route('admin.riwayat') }}" class="active">
                <i data-lucide="history"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <a href="{{ route('admin.laporan') }}">
                <i data-lucide="chart-no-axes-combined"></i>
                <span>Laporan</span>
            </a>

            <a href="{{ route('admin.pengaturan') }}">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
    </aside>

    <main class="admin-main">

        <header class="topbar">
            <div class="topbar-title">
                <h1>Riwayat Transaksi</h1>
                <p>Pilih jenis riwayat transaksi yang ingin dilihat.</p>
            </div>

            @include('admin.partials.profile-menu')
        </header>

        <section class="history-choice-grid">

            <a href="{{ route('admin.riwayat.online') }}" class="history-choice-card">
                <div class="history-choice-icon online">
                    <i data-lucide="credit-card"></i>
                </div>

                <div class="history-choice-content">
                    <h2>Riwayat Transaksi Online</h2>

                    <p>
                        Lihat transaksi pelanggan yang melakukan pemesanan
                        dan pembayaran melalui website.
                    </p>

                    <span>
                        Lihat Riwayat Online
                        <i data-lucide="arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="{{ route('admin.riwayat.offline') }}" class="history-choice-card">
                <div class="history-choice-icon offline">
                    <i data-lucide="banknote"></i>
                </div>

                <div class="history-choice-content">
                    <h2>Riwayat Transaksi Offline</h2>

                    <p>
                        Lihat transaksi pelanggan yang melakukan pembayaran
                        tunai melalui Kasir Utama.
                    </p>

                    <span>
                        Lihat Riwayat Offline
                        <i data-lucide="arrow-right"></i>
                    </span>
                </div>
            </a>

        </section>

    </main>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>

</body>
</html>