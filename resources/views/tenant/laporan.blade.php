<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tenant | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/tenant-laporan.css') }}?v={{ filemtime(public_path('css/tenant-laporan.css')) }}">
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
                <h1>Laporan</h1>
                <p>Ringkasan penjualan {{ $tenant->nama_tenant }}.</p>
            </div>

          @include('tenant.partials.profile-menu')
          
        </header>

        <section class="tenant-report-stat-grid">
            <div class="tenant-report-stat">
                <span>Total Pendapatan</span>
                <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                <small>Online + Offline</small>
            </div>

            <div class="tenant-report-stat">
                <span>Total Transaksi</span>
                <strong>{{ $totalTransaksi }}</strong>
                <small>Transaksi selesai</small>
            </div>

            <div class="tenant-report-stat">
                <span>Pendapatan Offline</span>
                <strong>Rp {{ number_format($totalOffline, 0, ',', '.') }}</strong>
                <small>{{ $jumlahOffline }} transaksi</small>
            </div>

            <div class="tenant-report-stat">
                <span>Pendapatan Online</span>
                <strong>Rp {{ number_format($totalOnline, 0, ',', '.') }}</strong>
                <small>{{ $jumlahOnline }} transaksi</small>
            </div>
        </section>

        <section class="tenant-report-grid">
            <div class="panel tenant-report-panel">
                <div class="panel-header">
                    <div>
                        <h2>Pendapatan Harian</h2>
                        <p>7 hari terakhir.</p>
                    </div>
                </div>

                <div class="tenant-chart-box">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <div class="panel tenant-report-panel">
                <div class="panel-header">
                    <div>
                        <h2>Pendapatan Mingguan</h2>
                        <p>4 minggu terakhir.</p>
                    </div>
                </div>

                <div class="tenant-chart-box">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </section>

        <section class="panel tenant-report-panel tenant-monthly-panel">
            <div class="panel-header">
                <div>
                    <h2>Pendapatan Bulanan</h2>
                    <p>6 bulan terakhir.</p>
                </div>
            </div>

            <div class="tenant-chart-box tenant-chart-large">
                <canvas id="monthlyChart"></canvas>
            </div>
        </section>

        <section class="panel tenant-panel">
            <div class="panel-header">
                <div>
                    <h2>Transaksi Terbaru</h2>
                    <p>5 transaksi terakhir yang telah selesai.</p>
                </div>
            </div>

            <div class="tenant-table-wrapper">
                <table class="tenant-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transaksiTerbaru as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['kode'] }}</strong>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    <span class="tenant-report-type {{ strtolower($item['jenis']) }}">
                                        {{ $item['jenis'] }}
                                    </span>
                                </td>

                                <td>
                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="tenant-empty-table">
                                    Belum ada transaksi selesai.
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script id="tenantReportData" type="application/json">
{!! json_encode([
    'harian' => [
        'labels' => $harianLabels,
        'offline' => $harianOffline,
        'online' => $harianOnline,
    ],
    'mingguan' => [
        'labels' => $mingguanLabels,
        'offline' => $mingguanOffline,
        'online' => $mingguanOnline,
    ],
    'bulanan' => [
        'labels' => $bulananLabels,
        'offline' => $bulananOffline,
        'online' => $bulananOnline,
    ],
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<script src="{{ asset('js/tenant-laporan.js') }}?v={{ filemtime(public_path('js/tenant-laporan.js')) }}"></script>

</body>
</html>