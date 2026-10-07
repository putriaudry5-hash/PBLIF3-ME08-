<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan | KantinKita</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}"
    >
</head>

<body>

<div class="admin-layout">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    <aside class="sidebar">

        <div class="sidebar-logo">

            <div class="logo-mark">
                K
            </div>

            <div>
                <h2>
                    Kantin<span>Kita</span>
                </h2>

                <small>
                    Kasir Utama / Admin
                </small>
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

            <a href="{{ route('admin.riwayat') }}">
                <i data-lucide="history"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <a
                href="{{ route('admin.laporan') }}"
                class="active"
            >
                <i data-lucide="chart-no-axes-combined"></i>
                <span>Laporan</span>
            </a>

            <a href="{{ route('admin.pengaturan') }}">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>

        </nav>

    </aside>


    {{-- =====================================================
         MAIN
    ====================================================== --}}
    <main class="admin-main">

        {{-- =================================================
             TOPBAR
        ================================================== --}}
        <header class="topbar">

            <div>
                <h1>Laporan</h1>

                <p>
                    Pantau transaksi dan pendapatan seluruh tenant.
                </p>
            </div>

        @include('admin.partials.profile-menu')

        </header>

        {{-- =================================================
             FILTER LAPORAN
        ================================================== --}}
        <section class="panel report-filter-panel">

            <div class="report-filter-header">

                <div>
                    <h2>
                        Periode Laporan
                    </h2>

                    <p>
                        Pilih laporan harian, mingguan, atau bulanan.
                    </p>
                </div>


                <span class="report-period-badge">

                    <i data-lucide="calendar-days"></i>

                    {{ $periodeLabel }}

                </span>

            </div>


            <form
                method="GET"
                action="{{ route('admin.laporan') }}"
                class="report-filter-form"
            >

                <input
                    type="hidden"
                    name="periode"
                    id="reportPeriod"
                    value="{{ $periode }}"
                >


                {{-- TAB PERIODE --}}
                <div class="report-period-tabs">

                    <button
                        type="button"
                        class="report-period-btn {{ $periode === 'harian' ? 'active' : '' }}"
                        data-period="harian"
                    >
                        <i data-lucide="calendar-days"></i>
                        <span>Harian</span>
                    </button>


                    <button
                        type="button"
                        class="report-period-btn {{ $periode === 'mingguan' ? 'active' : '' }}"
                        data-period="mingguan"
                    >
                        <i data-lucide="calendar-range"></i>
                        <span>Mingguan</span>
                    </button>


                    <button
                        type="button"
                        class="report-period-btn {{ $periode === 'bulanan' ? 'active' : '' }}"
                        data-period="bulanan"
                    >
                        <i data-lucide="calendar"></i>
                        <span>Bulanan</span>
                    </button>

                </div>


                {{-- FILTER --}}
                <div class="report-filter-grid">

                    {{-- HARIAN --}}
                    <div
                        class="offline-form-group report-period-field"
                        data-period-field="harian"
                    >

                        <label for="reportDate">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            id="reportDate"
                            name="tanggal"
                            value="{{ $tanggalInput }}"
                        >

                    </div>


                    {{-- MINGGUAN --}}
                    <div
                        class="offline-form-group report-period-field"
                        data-period-field="mingguan"
                    >

                        <label for="reportWeek">
                            Minggu
                        </label>

                        <input
                            type="week"
                            id="reportWeek"
                            name="minggu"
                            value="{{ $mingguInput }}"
                        >

                    </div>


                    {{-- BULANAN --}}
                    <div
                        class="offline-form-group report-period-field"
                        data-period-field="bulanan"
                    >

                        <label for="reportMonth">
                            Bulan
                        </label>

                        <input
                            type="month"
                            id="reportMonth"
                            name="bulan"
                            value="{{ $bulanInput }}"
                        >

                    </div>


                    {{-- FILTER TENANT --}}
                    <div class="offline-form-group">

                        <label for="reportTenant">
                            Tenant
                        </label>

                        <select
                            id="reportTenant"
                            name="tenant_id"
                        >

                            <option value="">
                                Semua Tenant
                            </option>

                            @foreach ($semuaTenants as $tenant)

                                <option
                                    value="{{ $tenant->id }}"
                                    {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}
                                >
                                    {{ $tenant->nama_tenant }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="report-filter-footer">

                    <a
                        href="{{ route('admin.laporan') }}"
                        class="report-reset-button"
                    >
                        Reset
                    </a>


                    <button
                        type="submit"
                        class="btn-offline-save"
                    >
                        <i data-lucide="filter"></i>
                        Tampilkan Laporan
                    </button>

                </div>

            </form>

        </section>


        {{-- =================================================
             SUMMARY
        ================================================== --}}
        <section class="report-summary-grid">

            <div class="report-summary-card">

                <div class="report-summary-icon">
                    <i data-lucide="receipt-text"></i>
                </div>

                <div>
                    <span>Total Transaksi</span>

                    <strong>
                        {{ $totalTransaksi }} Transaksi
                    </strong>
                </div>

            </div>


            <div class="report-summary-card">

                <div class="report-summary-icon">
                    <i data-lucide="wallet"></i>
                </div>

                <div>
                    <span>Total Pendapatan</span>

                    <strong>
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </strong>
                </div>

            </div>


            <div class="report-summary-card">

                <div class="report-summary-icon">
                    <i data-lucide="credit-card"></i>
                </div>

                <div>
                    <span>Pendapatan Online</span>

                    <strong>
                        Rp {{ number_format($pendapatanOnline, 0, ',', '.') }}
                    </strong>

                    <small>
                        {{ $jumlahOnline }} transaksi
                    </small>
                </div>

            </div>


            <div class="report-summary-card">

                <div class="report-summary-icon">
                    <i data-lucide="banknote"></i>
                </div>

                <div>
                    <span>Pendapatan Offline</span>

                    <strong>
                        Rp {{ number_format($pendapatanOffline, 0, ',', '.') }}
                    </strong>

                    <small>
                        {{ $jumlahOffline }} transaksi
                    </small>
                </div>

            </div>

        </section>


        {{-- =================================================
     GRAFIK HARIAN
================================================== --}}
<section class="panel report-chart-panel">

    <div class="report-section-header">

        <div>

            <h2>
                Grafik Pendapatan Harian
            </h2>

            <p>
                @if (request('tenant_id'))

                    Perbandingan pendapatan online dan offline
                    pada {{ $harianPeriodeLabel }}.

                @else

                    Perbandingan pendapatan setiap tenant
                    pada {{ $harianPeriodeLabel }}.

                @endif
            </p>

        </div>


        <span class="report-chart-badge">

            <i data-lucide="bar-chart-3"></i>

            Harian

        </span>

    </div>


    <div class="report-chart-wrapper">

        @if (array_sum($grafikHarianData) > 0)

            <canvas id="incomeChartDaily"></canvas>

        @else

            <div class="report-empty-chart">

                <i data-lucide="bar-chart-3"></i>

                <span>
                    Belum ada pendapatan harian.
                </span>

            </div>

        @endif

    </div>

</section>


{{-- =================================================
     GRAFIK MINGGUAN
================================================== --}}
<section class="panel report-chart-panel">

    <div class="report-section-header">

        <div>

            <h2>
                Grafik Pendapatan Mingguan
            </h2>

            <p>
                Pendapatan dari Senin sampai Minggu
                periode {{ $mingguanPeriodeLabel }}.
            </p>

        </div>


        <span class="report-chart-badge">

            <i data-lucide="chart-no-axes-combined"></i>

            Mingguan

        </span>

    </div>


    <div class="report-chart-wrapper">

        @if (array_sum($grafikMingguanData) > 0)

            <canvas id="incomeChartWeekly"></canvas>

        @else

            <div class="report-empty-chart">

                <i data-lucide="chart-no-axes-combined"></i>

                <span>
                    Belum ada pendapatan mingguan.
                </span>

            </div>

        @endif

    </div>

</section>


{{-- =================================================
     GRAFIK BULANAN
================================================== --}}
<section class="panel report-chart-panel">

    <div class="report-section-header">

        <div>

            <h2>
                Grafik Pendapatan Bulanan
            </h2>

            <p>
                Pendapatan berdasarkan tanggal pada
                {{ $bulananPeriodeLabel }}.
            </p>

        </div>


        <span class="report-chart-badge">

            <i data-lucide="chart-no-axes-combined"></i>

            Bulanan

        </span>

    </div>


    <div class="report-chart-wrapper">

        @if (array_sum($grafikBulananData) > 0)

            <canvas id="incomeChartMonthly"></canvas>

        @else

            <div class="report-empty-chart">

                <i data-lucide="chart-no-axes-combined"></i>

                <span>
                    Belum ada pendapatan bulanan.
                </span>

            </div>

        @endif

    </div>

</section>


        {{-- =================================================
             REKAP PER TENANT
        ================================================== --}}
        <section class="panel report-tenant-panel">

            <div class="report-section-header">

                <div>

                    <h2>
                        Rekap Per Tenant
                    </h2>

                    <p>
                        Pilih tenant untuk melihat rekap
                        dan detail transaksi.
                    </p>

                </div>

            </div>


            {{-- DROPDOWN TENANT --}}
            <form
                method="GET"
                action="{{ route('admin.laporan') }}"
                id="recapTenantForm"
                class="report-filter-form"
            >

                <input
                    type="hidden"
                    name="periode"
                    value="{{ $periode }}"
                >

                <input
                    type="hidden"
                    name="tanggal"
                    value="{{ $tanggalInput }}"
                >

                <input
                    type="hidden"
                    name="minggu"
                    value="{{ $mingguInput }}"
                >

                <input
                    type="hidden"
                    name="bulan"
                    value="{{ $bulanInput }}"
                >


                @if (request('tenant_id'))

                    <input
                        type="hidden"
                        name="tenant_id"
                        value="{{ request('tenant_id') }}"
                    >

                @endif


                <div class="report-filter-grid">

                    <div class="offline-form-group">

                        <label for="recapTenantSelect">
                            Pilih Tenant
                        </label>

                        <select
                            id="recapTenantSelect"
                            name="rekap_tenant_id"
                        >

                            <option value="">
                                Pilih Tenant
                            </option>


                            @foreach ($semuaTenants as $tenant)

                                <option
                                    value="{{ $tenant->id }}"
                                    {{ (string) $rekapTenantId === (string) $tenant->id ? 'selected' : '' }}
                                >
                                    {{ $tenant->nama_tenant }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </form>


            @if ($tenantRekapTerpilih && $rekapTenantData)

                {{-- =========================================
                     NAMA TENANT
                ========================================== --}}
                <div class="report-section-header">

                    <div>

                        <h2>
                            {{ $tenantRekapTerpilih->nama_tenant }}
                        </h2>

                        <p>
                            Rekap periode {{ $periodeLabel }}.
                        </p>

                    </div>

                </div>


                {{-- =========================================
                     DETAIL RINGKASAN TENANT
                ========================================== --}}
                <section class="report-summary-grid">

                    <div class="report-summary-card">

                        <div class="report-summary-icon">
                            <i data-lucide="receipt-text"></i>
                        </div>

                        <div>

                            <span>Total Transaksi</span>

                            <strong>
                                {{ $rekapTenantData['total_transaksi'] }}
                                Transaksi
                            </strong>

                        </div>

                    </div>


                    <div class="report-summary-card">

                        <div class="report-summary-icon">
                            <i data-lucide="wallet"></i>
                        </div>

                        <div>

                            <span>Total Pendapatan</span>

                            <strong>
                                Rp {{ number_format(
                                    $rekapTenantData['total_pendapatan'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>

                        </div>

                    </div>


                    <div class="report-summary-card">

                        <div class="report-summary-icon">
                            <i data-lucide="credit-card"></i>
                        </div>

                        <div>

                            <span>Transaksi Online</span>

                            <strong>
                                {{ $rekapTenantData['jumlah_online'] }}
                                Transaksi
                            </strong>

                            <small>
                                Rp {{ number_format(
                                    $rekapTenantData['pendapatan_online'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </small>

                        </div>

                    </div>


                    <div class="report-summary-card">

                        <div class="report-summary-icon">
                            <i data-lucide="banknote"></i>
                        </div>

                        <div>

                            <span>Transaksi Offline</span>

                            <strong>
                                {{ $rekapTenantData['jumlah_offline'] }}
                                Transaksi
                            </strong>

                            <small>
                                Rp {{ number_format(
                                    $rekapTenantData['pendapatan_offline'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </small>

                        </div>

                    </div>


                    <div class="report-summary-card">

                        <div class="report-summary-icon">
                            <i data-lucide="calculator"></i>
                        </div>

                        <div>

                            <span>Rata-rata Transaksi</span>

                            <strong>
                                Rp {{ number_format(
                                    $rekapTenantData['rata_rata'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>

                        </div>

                    </div>


                    <div class="report-summary-card">

                        <div class="report-summary-icon">
                            <i data-lucide="clock-3"></i>
                        </div>

                        <div>

                            <span>Transaksi Terakhir</span>

                            <strong>

                                @if ($rekapTenantData['transaksi_terakhir'])

                                    {{
                                        $rekapTenantData[
                                            'transaksi_terakhir'
                                        ]
                                        ->created_at
                                        ->format('d/m/Y H:i')
                                    }}

                                @else

                                    -

                                @endif

                            </strong>

                        </div>

                    </div>

                </section>


                {{-- =========================================
                     DAFTAR TRANSAKSI TENANT
                ========================================== --}}
                <div class="report-section-header">

                    <div>

                        <h2>
                            Detail Transaksi
                        </h2>

                        <p>
                            Seluruh transaksi lunas
                            {{ $tenantRekapTerpilih->nama_tenant }}
                            pada periode ini.
                        </p>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="offline-table">

                        <thead>

                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Metode Pembayaran</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>

                        </thead>


                        <tbody>

                        @forelse ($transaksiRekapTenant as $item)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $item->kode_transaksi }}
                                    </strong>
                                </td>


                                <td>
                                    {{
                                        $item->created_at
                                            ->format('d/m/Y H:i')
                                    }}
                                </td>


                                <td>
                                    {{ ucfirst($item->jenis) }}
                                </td>


                                <td>
                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $item->metode_pembayaran
                                            )
                                        )
                                    }}
                                </td>


                                <td>
                                    <strong>
                                        Rp {{ number_format(
                                            $item->total,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </strong>
                                </td>


                                <td>
                                    <span class="status-badge success">
                                        Lunas
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="offline-empty"
                                >
                                    Belum ada transaksi lunas
                                    untuk tenant ini pada periode
                                    yang dipilih.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            @else

                <div class="offline-empty">

                    Pilih tenant pada dropdown di atas
                    untuk melihat rekap lengkap.

                </div>

            @endif

        </section>

    </main>

</div>


{{-- =========================================================
     DATA GRAFIK
========================================================= --}}
{{-- =========================================================
     DATA GRAFIK HARIAN
========================================================= --}}
<div
    id="reportChartDailyData"
    data-labels='@json($grafikHarianLabel)'
    data-values='@json($grafikHarianData)'
    hidden
></div>


{{-- =========================================================
     DATA GRAFIK MINGGUAN
========================================================= --}}
<div
    id="reportChartWeeklyData"
    data-labels='@json($grafikMingguanLabel)'
    data-values='@json($grafikMingguanData)'
    hidden
></div>


{{-- =========================================================
     DATA GRAFIK BULANAN
========================================================= --}}
<div
    id="reportChartMonthlyData"
    data-labels='@json($grafikBulananLabel)'
    data-values='@json($grafikBulananData)'
    hidden
></div>


<script src="https://unpkg.com/lucide@latest"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script
    src="{{ asset('js/admin-laporan.js') }}?v={{ filemtime(public_path('js/admin-laporan.js')) }}"
></script>

</body>
</html>