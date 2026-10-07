<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Online | KantinKita</title>
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

            <a href="{{ route('admin.transaksi.online') }}" class="active">
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

    </aside>

    <main class="admin-main">

        <header class="topbar">
            <div>
                <h1>Transaksi Online</h1>
                <p>Pantau transaksi online setiap tenant secara langsung.</p>
            </div>

            @include('admin.partials.profile-menu')

        </header>

        <section class="offline-summary-grid">

            <div class="offline-summary-card">
                <div class="offline-summary-icon">
                    <i data-lucide="shopping-bag"></i>
                </div>

                <div>
                    <span>Transaksi Online Hari Ini</span>
                    <strong>{{ $jumlahHariIni }} Transaksi</strong>
                </div>
            </div>

            <div class="offline-summary-card">
                <div class="offline-summary-icon">
                    <i data-lucide="wallet-cards"></i>
                </div>

                <div>
                    <span>Pendapatan Online Hari Ini</span>

                    <strong>
                        Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

        </section>

        <div class="online-tenant-list">

            @foreach ($semuaTenants as $tenant)

                @php
                    $daftarTransaksi = $transaksiPerTenant->get(
                        $tenant->id,
                        collect()
                    );

                    $jumlahTransaksiTenant = $daftarTransaksi->count();

                    $pendapatanTenant = $daftarTransaksi
                        ->where('status', 'lunas')
                        ->sum('total');
                @endphp

                <section class="panel online-tenant-panel">

                    <div class="online-tenant-header">

                        <div class="online-tenant-title">
                            <div class="online-tenant-icon">
                                <i data-lucide="store"></i>
                            </div>

                            <div>
                                <h2>{{ $tenant->nama_tenant }}</h2>

                                <p>
                                    {{ $jumlahTransaksiTenant }} transaksi online hari ini
                                </p>
                            </div>
                        </div>

                        <div class="online-tenant-summary">
                            <span>Pendapatan</span>

                            <strong>
                                Rp {{ number_format(
                                    $pendapatanTenant,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>
                        </div>

                    </div>

                    <div class="table-wrapper">

                        <table class="offline-table">

                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Waktu</th>
                                    <th>Total</th>
                                    <th>Metode</th>
                                    <th>Pembayaran</th>
                                    <th>Status Pesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            @forelse ($daftarTransaksi as $transaksi)

                                <tr>
                                    <td>
                                        <strong>
                                            {{ $transaksi->kode_transaksi }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{
                                            $transaksi->pelanggan?->nama
                                            ?? $transaksi->pelanggan?->nama_lengkap
                                            ?? '-'
                                        }}
                                    </td>

                                    <td>
                                        {{ $transaksi->created_at->format('H:i') }}
                                    </td>

                                    <td>
                                        <strong>
                                            Rp {{ number_format(
                                                $transaksi->total,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ strtoupper(
                                            $transaksi->metode_pembayaran ?? '-'
                                        ) }}
                                    </td>

                                    <td>
                                        @if ($transaksi->status === 'lunas')
                                            <span class="status-success">
                                                Lunas
                                            </span>

                                        @elseif ($transaksi->status === 'menunggu_pembayaran')
                                            <span class="status-waiting">
                                                Menunggu Pembayaran
                                            </span>

                                        @else
                                            <span class="tenant-status inactive">
                                                {{ ucwords(str_replace(
                                                    '_',
                                                    ' ',
                                                    $transaksi->status
                                                )) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($transaksi->status_pesanan === 'diproses')
                                            <span class="status-process">
                                                Diproses
                                            </span>

                                        @elseif ($transaksi->status_pesanan === 'siap_diambil')
                                            <span class="status-success">
                                                Siap Diambil
                                            </span>

                                        @elseif ($transaksi->status_pesanan === 'selesai')
                                            <span class="status-success">
                                                Selesai
                                            </span>

                                        @else
                                            <span class="status-waiting">
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <button
                                            type="button"
                                            class="btn-detail online-detail-btn"
                                            data-id="{{ $transaksi->id }}"
                                        >
                                            Detail
                                        </button>
                                    </td>
                                </tr>

                                <tr
                                    id="onlineDetail{{ $transaksi->id }}"
                                    class="online-detail-row"
                                    style="display: none;"
                                >
                                    <td colspan="8">

                                        <div class="online-detail-box">

                                            <div class="online-detail-header">
                                                <div>
                                                    <strong>Detail Pesanan</strong>

                                                    <small>
                                                        Kode:
                                                        {{ $transaksi->kode_transaksi }}
                                                    </small>
                                                </div>

                                                <strong>
                                                    {{ $transaksi->details->sum('jumlah') }}
                                                    Item
                                                </strong>
                                            </div>

                                            <div class="table-wrapper">

                                                <table class="offline-table">

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
                                                                <strong>
                                                                    {{ $detail->nama_item }}
                                                                </strong>
                                                            </td>

                                                            <td>
                                                                Rp {{ number_format(
                                                                    $detail->harga,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </td>

                                                            <td>
                                                                {{ $detail->jumlah }}
                                                            </td>

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
                                                            <td
                                                                colspan="4"
                                                                class="offline-empty"
                                                            >
                                                                Detail pesanan belum tersedia.
                                                            </td>
                                                        </tr>

                                                    @endforelse

                                                    </tbody>

                                                </table>

                                            </div>

                                            <div class="online-detail-total">
                                                <span>Total Belanja</span>

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

                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="8"
                                        class="offline-empty"
                                    >
                                        Belum ada transaksi online
                                        untuk tenant ini hari ini.
                                    </td>
                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </section>

            @endforeach

        </div>

    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('js/admin-transaksi-online.js') }}"></script>

</body>
</html>