<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaksi Offline | KantinKita</title>

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

            <a
                href="{{ route('admin.transaksi.offline') }}"
                class="active"
            >
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
            <a href="{{ route('login.internal') }}">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>


    <main class="admin-main">

        <header class="topbar">
            <div>
                <h1>Transaksi Offline</h1>
                <p>
                    Cari dan konfirmasi pembayaran transaksi offline dari tenant.
                </p>
            </div>

            <div class="admin-profile">
                <button
                    type="button"
                    class="notification"
                    aria-label="Notifikasi"
                >
                    <i data-lucide="bell"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="admin-avatar">
                    A
                </div>

                <div class="admin-info">
                    <strong>Admin Utama</strong>
                    <small>Kasir Utama / Admin</small>
                </div>
            </div>
        </header>


        @if (session('success'))
            <div
                class="alert-success"
                id="successAlert"
            >
                {{ session('success') }}
            </div>
        @endif


        @if (session('error'))
            <div class="form-error">
                {{ session('error') }}
            </div>
        @endif


        <section class="offline-summary-grid">

            <div class="offline-summary-card">
                <div class="offline-summary-icon">
                    <i data-lucide="receipt-text"></i>
                </div>

                <div>
                    <span>Transaksi Hari Ini</span>

                    <strong>
                        {{ $jumlahHariIni }} Transaksi
                    </strong>
                </div>
            </div>


            <div class="offline-summary-card">
                <div class="offline-summary-icon">
                    <i data-lucide="wallet"></i>
                </div>

                <div>
                    <span>Pendapatan Offline Hari Ini</span>

                    <strong>
                        Rp {{ number_format(
                            $pendapatanHariIni,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>
            </div>

        </section>


        <section class="panel offline-form-panel">

            <div class="offline-panel-header">
                <div>
                    <h2>
                        Cari Transaksi Pelanggan
                    </h2>

                    <p>
                        Masukkan kode yang diberikan tenant
                        untuk memproses pembayaran pelanggan.
                    </p>
                </div>

                <span class="offline-cash-badge">
                    <i data-lucide="banknote"></i>
                    Pembayaran Tunai
                </span>
            </div>


            <form
    method="GET"
    action="{{ route('admin.transaksi.offline') }}"
    class="offline-form"
>
    <div class="offline-form-group">
        <label for="offlineTenant">
            Pilih Tenant
        </label>

        <select
            id="offlineTenant"
            name="tenant_id"
            required
        >
            <option value="">
                Pilih tenant
            </option>

            @foreach ($semuaTenants as $tenant)
                <option
                    value="{{ $tenant->id }}"
                    {{ request('tenant_id') == $tenant->id
                        ? 'selected'
                        : ''
                    }}
                >
                    {{ $tenant->nama_tenant }}
                </option>
            @endforeach
        </select>
    </div>

    <div
        class="offline-form-group"
        style="margin-top: 14px;"
    >
        <label for="offlineTransactionCode">
            Kode Transaksi
        </label>

        <input
            type="text"
            id="offlineTransactionCode"
            name="kode_transaksi"
            value="{{ request('kode_transaksi') }}"
            placeholder="Contoh: 4.1"
            autocomplete="off"
            required
        >
    </div>

    <div class="offline-form-footer">
        <div class="offline-payment-info">
            <i data-lucide="info"></i>

            <span>
                Pilih tenant terlebih dahulu,
                lalu masukkan kode transaksi pelanggan.
            </span>
        </div>

        <div class="offline-form-actions">
            <button
                type="submit"
                class="btn-offline-save"
            >
                <i data-lucide="search"></i>
                Cari Transaksi
            </button>
        </div>
    </div>
</form>


            @if (request()->filled('kode_transaksi'))

                @if ($transaksiDicari)

                    <div
                        class="offline-transaction-detail"
                        style="margin-top: 24px;"
                    >

                        <div class="offline-panel-header">

                            <div>
                                <h2>
                                    Detail Transaksi
                                </h2>

                                <p>
                                    Periksa transaksi sebelum
                                    menerima pembayaran.
                                </p>
                            </div>


                            @if ($transaksiDicari->status === 'lunas')
                                <span class="offline-status-paid">
                                    Lunas
                                </span>
                            @else
                                <span class="offline-status-waiting">
                                    Menunggu Pembayaran
                                </span>
                            @endif

                        </div>


                        <div class="offline-form-grid">

                            <div class="offline-form-group">
                                <label>
                                    Kode Transaksi
                                </label>

                                <strong>
                                    {{ $transaksiDicari->kode_transaksi }}
                                </strong>
                            </div>


                            <div class="offline-form-group">
                                <label>
                                    Tenant
                                </label>

                                <strong>
                                    {{ $transaksiDicari->tenant->nama_tenant ?? '-' }}
                                </strong>
                            </div>


                            <div class="offline-form-group">
                                <label>
                                    Waktu Transaksi
                                </label>

                                <strong>
                                    {{ $transaksiDicari->created_at->format('d/m/Y H:i') }}
                                </strong>
                            </div>


                            <div class="offline-form-group">
                                <label>
                                    Total Belanja
                                </label>

                                <strong>
                                    Rp {{ number_format(
                                        $transaksiDicari->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </div>

                        </div>


                        @if ($transaksiDicari->status !== 'lunas')

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.transaksi.offline.bayar',
                                    $transaksiDicari->id
                                ) }}"
                                class="offline-form"
                                style="margin-top: 20px;"
                            >

                                @csrf
                                @method('PATCH')


                                <input
                                    type="hidden"
                                    id="offlineTotal"
                                    value="{{ $transaksiDicari->total }}"
                                >


                                <div class="offline-form-grid">

                                    <div class="offline-form-group">
                                        <label for="offlinePayment">
                                            Uang Diterima
                                        </label>

                                        <input
                                            type="number"
                                            id="offlinePayment"
                                            name="jumlah_bayar"
                                            value="{{ old('jumlah_bayar') }}"
                                            min="{{ $transaksiDicari->total }}"
                                            placeholder="Contoh: 50000"
                                            oninput="calculateChange()"
                                            required
                                        >
                                    </div>


                                    <div
                                        class="
                                            offline-form-group
                                            offline-change-box
                                        "
                                    >
                                        <label>
                                            Kembalian
                                        </label>

                                        <strong id="offlineChange">
                                            Rp 0
                                        </strong>

                                        <small id="offlineChangeInfo">
                                            Masukkan uang yang diterima.
                                        </small>
                                    </div>

                                </div>


                                @if ($errors->any())

                                    <div class="form-error">
                                        {{ $errors->first() }}
                                    </div>

                                @endif


                                <div class="offline-form-footer">

                                    <div class="offline-payment-info">
                                        <i data-lucide="info"></i>

                                        <span>
                                            Setelah dikonfirmasi,
                                            status transaksi akan
                                            berubah menjadi lunas.
                                        </span>
                                    </div>


                                    <div class="offline-form-actions">
                                        <button
                                            type="submit"
                                            class="btn-offline-save"
                                        >
                                            <i data-lucide="check-circle"></i>
                                            Konfirmasi Pembayaran
                                        </button>
                                    </div>

                                </div>

                            </form>


                        @else

                            <div
                                class="offline-payment-info"
                                style="margin-top: 20px;"
                            >
                                <i data-lucide="circle-check"></i>

                                <span>
                                    Transaksi ini sudah lunas.

                                    Uang diterima:

                                    <strong>
                                        Rp {{ number_format(
                                            $transaksiDicari->jumlah_bayar,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </strong>

                                    —

                                    Kembalian:

                                    <strong>
                                        Rp {{ number_format(
                                            $transaksiDicari->kembalian,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </strong>
                                </span>
                            </div>

                        @endif

                    </div>


                @else

                    <div
                        class="form-error"
                        style="margin-top: 20px;"
                    >
                        Kode transaksi

                        <strong>
                            {{ request('kode_transaksi') }}
                        </strong>

                        tidak ditemukan untuk hari ini.
                    </div>

                @endif

            @endif

        </section>


        <section
            class="panel offline-history-panel"
            id="offlineHistorySection"
        >

            <div class="offline-history-header">

                <div class="offline-history-info">
                    <h2>
                        Daftar Transaksi Offline
                    </h2>

                    <p>
                        Pantau transaksi offline
                        dari seluruh tenant hari ini.
                    </p>
                </div>


                <form
                    method="GET"
                    action="{{ route('admin.transaksi.offline') }}"
                    class="offline-view-select"
                >

                    <label for="tenantFilter">
                        Lihat Transaksi
                    </label>

                    <select
                        id="tenantFilter"
                        name="tenant_id"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Tenant
                        </option>


                        @foreach ($semuaTenants as $tenant)

                            <option
                                value="{{ $tenant->id }}"
                                {{ request('tenant_id') == $tenant->id
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                {{ $tenant->nama_tenant }}
                            </option>

                        @endforeach

                    </select>

                </form>

            </div>


            <div class="table-wrapper">

                <table class="offline-table">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tenant</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Status</th>
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
                                    {{ $transaksi->tenant->nama_tenant ?? '-' }}
                                </td>


                                <td>
                                    {{ $transaksi->created_at->format('d/m/Y H:i') }}
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

                                    @if ($transaksi->status === 'lunas')

                                        <span class="offline-status-paid">
                                            Lunas
                                        </span>

                                    @else

                                        <span class="offline-cash-badge">
                                            Menunggu Pembayaran
                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="offline-empty"
                                >
                                    Belum ada transaksi offline
                                    dari tenant hari ini.
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

<script src="{{ asset('js/admin-transaksi-offline.js') }}"></script>

</body>
</html>