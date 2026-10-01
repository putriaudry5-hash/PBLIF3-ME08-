<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .sidebar-bottom form,
        .profile-menu form {
            width: 100%;
            margin: 0;
        }

        .sidebar-logout-button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #d9e3ec;
            font: inherit;
            cursor: pointer;
            text-align: left;
        }

        .sidebar-logout-button:hover {
            background: rgba(255, 255, 255, .08);
            color: #fff;
        }

        .sidebar-logout-button svg {
            width: 18px;
            height: 18px;
        }

        .admin-profile-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 7px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            font-family: inherit;
            cursor: pointer;
            transition: .15s ease;
        }

        .profile-trigger:hover,
        .profile-trigger.open {
            background: #eef2f5;
        }

        .profile-trigger .admin-info {
            text-align: left;
        }

        .profile-chevron {
            width: 15px;
            height: 15px;
            color: #8492a1;
            transition: .18s ease;
        }

        .profile-trigger.open .profile-chevron {
            transform: rotate(180deg);
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            z-index: 1000;
            width: 265px;
            padding: 8px;
            border: 1px solid #dce3e9;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(25, 50, 77, .14);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: .18s ease;
        }

        .profile-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
        }

        .profile-menu-avatar {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 50%;
            background: #fff0df;
            color: #ef7d1a;
            font-size: 14px;
            font-weight: 700;
        }

        .profile-menu-user {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .profile-menu-user strong {
            color: #17233c;
            font-size: 12px;
        }

        .profile-menu-user span {
            overflow: hidden;
            color: #8793a0;
            font-size: 10px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .profile-role {
            display: flex;
            align-items: center;
            gap: 7px;
            margin: 2px 6px 5px;
            padding: 7px 9px;
            border-radius: 6px;
            background: #f4f7f9;
            color: #627284;
            font-size: 10px;
        }

        .profile-role svg {
            width: 14px;
            height: 14px;
        }

        .profile-divider {
            height: 1px;
            margin: 7px 4px;
            background: #edf0f2;
        }

        .profile-menu-item {
            width: 100%;
            min-height: 38px;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 0 10px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: #314254;
            font-family: inherit;
            font-size: 11px;
            text-decoration: none;
            cursor: pointer;
        }

        .profile-menu-item:hover {
            background: #f4f6f8;
        }

        .profile-menu-item svg {
            width: 15px;
            height: 15px;
        }

        .profile-logout {
            color: #c3423f;
        }

        .profile-logout:hover {
            background: #fff1f0;
        }

        .btn-table,
        .btn-detail,
        .offline-button {
            text-decoration: none;
        }

        .dashboard-empty-row td {
            padding: 34px 20px !important;
            text-align: center;
        }

        .dashboard-empty-table {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            color: #8a97a5;
        }

        .dashboard-empty-table svg {
            width: 25px;
            height: 25px;
            color: #7c8d9f;
        }

        .dashboard-empty-table strong {
            color: #45576a;
            font-size: 12px;
        }

        .dashboard-empty-table span {
            font-size: 10px;
        }

        .dashboard-empty {
            grid-column: 1 / -1;
            min-height: 125px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            border: 1px dashed #d5dde5;
            border-radius: 9px;
            color: #8290a0;
        }

        .dashboard-empty svg {
            width: 25px;
            height: 25px;
            color: #4e9a72;
        }

        .dashboard-empty div {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .dashboard-empty strong {
            color: #33475b;
            font-size: 12px;
        }

        .dashboard-empty span {
            font-size: 10px;
        }

        @media (max-width: 650px) {
            .profile-trigger .admin-info,
            .profile-chevron {
                display: none;
            }

            .admin-profile-area {
                gap: 5px;
            }
        }
    </style>
</head>

<body>
@php
    $admin = auth('internal')->user();
@endphp

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
            <a href="{{ route('admin.dashboard') }}" class="active">
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

            <a href="{{ route('admin.riwayat') }}">
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
            <form method="POST" action="{{ route('login.internal.logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout-button">
                    <i data-lucide="log-out"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <header class="topbar">
            <div class="topbar-title">
                <h1>Dashboard</h1>
                <p>Ringkasan aktivitas KantinKita hari ini.</p>
            </div>

            <div class="admin-profile-area">
                <button
                    type="button"
                    class="notification"
                    aria-label="Notifikasi"
                >
                    <i data-lucide="bell"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="profile-dropdown">
                    <button
                        type="button"
                        class="admin-profile profile-trigger"
                        id="profileTrigger"
                        aria-expanded="false"
                    >
                        <div class="admin-avatar">
                            {{ strtoupper(substr($admin->nama ?? 'Admin', 0, 1)) }}
                        </div>

                        <div class="admin-info">
                            <strong>{{ $admin->nama ?? 'Admin Utama' }}</strong>
                            <small>Kasir Utama / Admin</small>
                        </div>

                        <i
                            data-lucide="chevron-down"
                            class="profile-chevron"
                        ></i>
                    </button>

                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-menu-header">
                            <div class="profile-menu-avatar">
                                {{ strtoupper(substr($admin->nama ?? 'Admin', 0, 1)) }}
                            </div>

                            <div class="profile-menu-user">
                                <strong>{{ $admin->nama ?? 'Admin Utama' }}</strong>
                                <span>{{ $admin->email ?? 'admin@kantinkita.com' }}</span>
                            </div>
                        </div>

                        <div class="profile-role">
                            <i data-lucide="shield-check"></i>
                            <span>Kasir Utama / Admin</span>
                        </div>

                        <div class="profile-divider"></div>

                        <a
                            href="{{ route('admin.pengaturan') }}"
                            class="profile-menu-item"
                        >
                            <i data-lucide="settings"></i>
                            <span>Pengaturan Akun</span>
                        </a>

                        <div class="profile-divider"></div>

                        <form
                            method="POST"
                            action="{{ route('login.internal.logout') }}"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="profile-menu-item profile-logout"
                            >
                                <i data-lucide="log-out"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <section class="stat-grid">
            <div class="stat-card stat-blue">
                <div class="stat-icon">
                    <i data-lucide="store"></i>
                </div>
                <div>
                    <p>Total Tenant Aktif</p>
                    <h2>{{ $totalTenantAktif ?? 0 }}</h2>
                    <small>Seluruh tenant kantin</small>
                </div>
            </div>

            <div class="stat-card stat-orange">
                <div class="stat-icon">
                    <i data-lucide="receipt-text"></i>
                </div>
                <div>
                    <p>Transaksi Hari Ini</p>
                    <h2>{{ $transaksiHariIni ?? 0 }}</h2>
                    <small>Online & Offline</small>
                </div>
            </div>

            <div class="stat-card stat-yellow">
                <div class="stat-icon">
                    <i data-lucide="clock-3"></i>
                </div>
                <div>
                    <p>Menunggu Pembayaran</p>
                    <h2>{{ $menungguPembayaran ?? 0 }}</h2>
                    <small>Transaksi offline</small>
                </div>
            </div>

            <div class="stat-card stat-green">
                <div class="stat-icon">
                    <i data-lucide="wallet-cards"></i>
                </div>
                <div>
                    <p>Pendapatan Hari Ini</p>
                    <h2>
                        Rp {{ number_format(
                            $pendapatanHariIni ?? 0,
                            0,
                            ',',
                            '.'
                        ) }}
                    </h2>
                    <small>Seluruh tenant</small>
                </div>
            </div>
        </section>

        <section class="dashboard-grid">
            <div class="panel transaction-panel">
                <div class="panel-header">
                    <div>
                        <h2>Transaksi Terbaru</h2>
                        <p>Transaksi online dan offline terbaru.</p>
                    </div>

                    <a href="{{ route('admin.riwayat') }}">
                        Lihat Semua →
                    </a>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Waktu</th>
                                <th>Tenant</th>
                                <th>Total</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse (($transaksiTerbaru ?? collect()) as $transaksi)
                                @php
                                    $statusRaw = $transaksi->status;

                                    if (
                                        $transaksi->jenis === 'online' &&
                                        $transaksi->status === 'lunas' &&
                                        !empty($transaksi->status_pesanan)
                                    ) {
                                        $statusRaw = $transaksi->status_pesanan;
                                    }

                                    $statusLabel = match ($statusRaw) {
                                        'menunggu_pembayaran' => 'Menunggu Bayar',
                                        'lunas' => 'Lunas',
                                        'gagal' => 'Gagal',
                                        'menunggu' => 'Menunggu',
                                        'diproses' => 'Diproses',
                                        'siap_diambil' => 'Siap Diambil',
                                        'selesai' => 'Selesai',
                                        default => ucfirst(
                                            str_replace('_', ' ', $statusRaw ?? '-')
                                        ),
                                    };

                                    $statusClass = match ($statusRaw) {
                                        'lunas', 'selesai' => 'status-success',
                                        'menunggu_pembayaran', 'menunggu' => 'status-waiting',
                                        default => 'status-process',
                                    };
                                @endphp

                                <tr>
                                    <td>
                                        <strong>
                                            {{ $transaksi->kode_transaksi }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $transaksi->created_at?->format('H:i') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $transaksi->tenant->nama_tenant ?? '-' }}
                                    </td>

                                    <td>
                                        Rp {{ number_format(
                                            $transaksi->total ?? 0,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                    <td>
                                        @if ($transaksi->jenis === 'online')
                                            <span class="type-online">Online</span>
                                        @else
                                            <span class="type-offline">Offline</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="{{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        @if (
                                            $transaksi->jenis === 'offline' &&
                                            $transaksi->status === 'menunggu_pembayaran'
                                        )
                                            <a
                                                href="{{ route(
                                                    'admin.transaksi.offline',
                                                    [
                                                        'tenant_id' => $transaksi->tenant_id,
                                                        'kode_transaksi' => $transaksi->kode_transaksi
                                                    ]
                                                ) }}"
                                                class="btn-table"
                                            >
                                                Proses
                                            </a>
                                        @elseif ($transaksi->jenis === 'offline')
                                            <a
                                                href="{{ route('admin.riwayat.offline') }}"
                                                class="btn-detail"
                                            >
                                                Lihat
                                            </a>
                                        @elseif (
                                            $transaksi->status_pesanan === 'selesai'
                                        )
                                            <a
                                                href="{{ route('admin.riwayat.online') }}"
                                                class="btn-detail"
                                            >
                                                Lihat
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('admin.transaksi.online') }}"
                                                class="btn-detail"
                                            >
                                                Lihat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="dashboard-empty-row">
                                    <td colspan="7">
                                        <div class="dashboard-empty-table">
                                            <i data-lucide="receipt-text"></i>
                                            <strong>Belum ada transaksi</strong>
                                            <span>
                                                Transaksi akan muncul setelah ada
                                                aktivitas nyata di sistem.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel quick-panel">
                <div class="panel-header">
                    <div>
                        <h2>Akses Cepat</h2>
                        <p>Menu yang sering digunakan.</p>
                    </div>
                </div>

                <div class="quick-list">
                    <a
                        href="{{ route('admin.transaksi.offline') }}"
                        class="quick-item"
                    >
                        <div class="quick-icon orange">
                            <i data-lucide="receipt-text"></i>
                        </div>

                        <div>
                            <strong>Transaksi Offline</strong>
                            <span>Cari kode & konfirmasi cash</span>
                        </div>

                        <i
                            class="quick-arrow"
                            data-lucide="chevron-right"
                        ></i>
                    </a>

                    <a
                        href="{{ route('admin.tenant') }}"
                        class="quick-item"
                    >
                        <div class="quick-icon blue">
                            <i data-lucide="store"></i>
                        </div>

                        <div>
                            <strong>Data Tenant</strong>
                            <span>Kelola akun tenant</span>
                        </div>

                        <i
                            class="quick-arrow"
                            data-lucide="chevron-right"
                        ></i>
                    </a>

                    <a
                        href="{{ route('admin.laporan') }}"
                        class="quick-item"
                    >
                        <div class="quick-icon green">
                            <i data-lucide="chart-no-axes-combined"></i>
                        </div>

                        <div>
                            <strong>Laporan</strong>
                            <span>Lihat rekap seluruh tenant</span>
                        </div>

                        <i
                            class="quick-arrow"
                            data-lucide="chevron-right"
                        ></i>
                    </a>
                </div>
            </div>
        </section>

        <section class="panel offline-panel">
            <div class="panel-header">
                <div>
                    <h2>Transaksi Offline Menunggu Pembayaran</h2>
                    <p>
                        Pesanan yang sudah dicatat Tenant dan belum
                        dikonfirmasi pembayaran oleh Kasir.
                    </p>
                </div>

                <span class="counter-badge">
                    {{ $jumlahOfflineMenunggu ?? 0 }} Menunggu
                </span>
            </div>

            <div class="offline-grid">
                @forelse (($offlineMenunggu ?? collect()) as $transaksi)
                    <div class="offline-card">
                        <div class="offline-top">
                            <div>
                                <small>Kode Transaksi</small>
                                <h3>{{ $transaksi->kode_transaksi }}</h3>
                            </div>

                            <span class="status-waiting">
                                Menunggu Bayar
                            </span>
                        </div>

                        <div class="offline-data">
                            <div>
                                <small>Tenant</small>
                                <strong>
                                    {{ $transaksi->tenant->nama_tenant ?? '-' }}
                                </strong>
                            </div>

                            <div>
                                <small>Total</small>
                                <strong>
                                    Rp {{ number_format(
                                        $transaksi->total ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </div>
                        </div>

                        <a
                            href="{{ route(
                                'admin.transaksi.offline',
                                [
                                    'tenant_id' => $transaksi->tenant_id,
                                    'kode_transaksi' => $transaksi->kode_transaksi
                                ]
                            ) }}"
                            class="offline-button"
                        >
                            Proses Pembayaran
                        </a>
                    </div>
                @empty
                    <div class="dashboard-empty">
                        <i data-lucide="circle-check"></i>

                        <div>
                            <strong>
                                Tidak ada transaksi menunggu pembayaran
                            </strong>

                            <span>
                                Transaksi offline yang belum dibayar
                                akan muncul di bagian ini.
                            </span>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const trigger = document.getElementById('profileTrigger');
    const menu = document.getElementById('profileMenu');

    if (!trigger || !menu) {
        return;
    }

    trigger.addEventListener('click', function (event) {
        event.stopPropagation();

        const active = menu.classList.toggle('show');

        trigger.classList.toggle('open', active);
        trigger.setAttribute(
            'aria-expanded',
            active ? 'true' : 'false'
        );
    });

    menu.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    document.addEventListener('click', function () {
        menu.classList.remove('show');
        trigger.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            menu.classList.remove('show');
            trigger.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>
</body>
</html>