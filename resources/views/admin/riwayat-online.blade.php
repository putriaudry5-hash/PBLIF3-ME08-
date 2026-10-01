<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Online | KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

        <div class="sidebar-bottom">
            <a href="{{ route('login.internal') }}">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="topbar">
            <div>
                <h1>Riwayat Transaksi Online</h1>
                <p>Daftar pesanan online yang sudah selesai.</p>
            </div>

            <div class="admin-profile">
                <button type="button" class="notification" aria-label="Notifikasi">
                    <i data-lucide="bell"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="admin-avatar">A</div>

                <div class="admin-info">
                    <strong>Admin Utama</strong>
                    <small>Kasir Utama / Admin</small>
                </div>
            </div>
        </header>

        <section class="offline-summary-grid history-summary-grid">
            <div class="offline-summary-card">
                <div class="offline-summary-icon">
                    <i data-lucide="shopping-bag"></i>
                </div>

                <div>
                    <span>Total Transaksi Online</span>
                    <strong>{{ $jumlahTransaksi }} Transaksi</strong>
                </div>
            </div>

            <div class="offline-summary-card">
                <div class="offline-summary-icon">
                    <i data-lucide="wallet-cards"></i>
                </div>

                <div>
                    <span>Total Pendapatan Online</span>
                    <strong>
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        </section>

        <section class="panel history-panel">
            <div class="offline-history-header history-list-header">
                <div class="offline-history-info">
                    <h2>Daftar Riwayat Online</h2>
                    <p>Menampilkan pesanan online yang sudah selesai.</p>
                </div>

                <a href="{{ route('admin.riwayat') }}" class="history-back-button">
                    <i data-lucide="arrow-left"></i>
                    Kembali
                </a>
            </div>

            <div class="table-wrapper">
                <table class="offline-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Tenant</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse ($daftarTransaksi as $transaksi)
                        <tr>
                            <td>
                                <strong>{{ $transaksi->kode_transaksi }}</strong>
                            </td>

                            <td>
                                {{
                                    $transaksi->pelanggan?->nama
                                    ?? $transaksi->pelanggan?->nama_lengkap
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {{ $transaksi->tenant?->nama_tenant ?? '-' }}
                            </td>

                            <td>
                                {{ $transaksi->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <strong>
                                    Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                {{ strtoupper($transaksi->metode_pembayaran ?? '-') }}
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="btn-detail history-detail-btn"
                                    data-id="{{ $transaksi->id }}"
                                >
                                    Detail
                                </button>
                            </td>
                        </tr>

                        <tr
                            id="historyDetail{{ $transaksi->id }}"
                            class="history-detail-row"
                            style="display:none;"
                        >
                            <td colspan="7">
                                <div class="history-detail-box">
                                    <div class="history-detail-header">
                                        <div>
                                            <strong>Detail Pesanan</strong>
                                            <small>{{ $transaksi->kode_transaksi }}</small>
                                        </div>

                                        <div class="history-detail-info">
                                            <span>
                                                {{ $transaksi->details->sum('jumlah') }} Item
                                            </span>

                                            <strong>
                                                Rp {{ number_format(
                                                    $transaksi->total,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </strong>
                                        </div>
                                    </div>

                                    <div class="history-transaction-info">
                                        <div>
                                            <span>Pembayaran</span>
                                            <strong>Lunas</strong>
                                        </div>

                                        <div>
                                            <span>Status Pesanan</span>
                                            <strong>Selesai</strong>
                                        </div>

                                        <div>
                                            <span>Metode</span>
                                            <strong>
                                                {{ strtoupper(
                                                    $transaksi->metode_pembayaran ?? '-'
                                                ) }}
                                            </strong>
                                        </div>
                                    </div>

                                    <div class="table-wrapper">
                                        <table class="offline-table history-detail-table">
                                            <thead>
                                                <tr>
                                                    <th>Pesanan</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            @forelse ($transaksi->details as $detail)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $detail->nama_item }}</strong>
                                                    </td>

                                                    <td>
                                                        Rp {{ number_format(
                                                            $detail->harga,
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}
                                                    </td>

                                                    <td>{{ $detail->jumlah }}</td>

                                                    <td>
                                                        <strong>
                                                            Rp {{ number_format(
                                                                $detail->subtotal,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="offline-empty">
                                                        Detail pesanan belum tersedia.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="offline-empty">
                                Belum ada transaksi online yang selesai.
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
<script src="{{ asset('js/admin-riwayat-transaksi.js') }}"></script>
</body>
</html>