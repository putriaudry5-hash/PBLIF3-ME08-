<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Tenant | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/tenant-riwayat.css') }}?v={{ filemtime(public_path('css/tenant-riwayat.css')) }}">
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
                <h1>Riwayat Transaksi</h1>
                <p>Riwayat transaksi {{ $tenant->nama_tenant }}.</p>
            </div>

            @include('tenant.partials.profile-menu')
            
        </header>

        <section class="tenant-history-stat-grid">
            <div class="tenant-small-stat">
                <span>Total Transaksi</span>
                <strong>{{ $totalTransaksi }}</strong>
            </div>

            <div class="tenant-small-stat">
                <span>Total Pendapatan</span>
                <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
            </div>
        </section>

        <section class="tenant-action-panel">
            <div>
                <div class="tenant-action-icon">
                    <i data-lucide="history"></i>
                </div>

                <div>
                    <h2>Riwayat Transaksi</h2>
                    <p>Lihat transaksi offline dan online yang telah selesai.</p>
                </div>
            </div>

            <div class="tenant-history-filter">
                <a
                    href="{{ route('tenant.riwayat', ['jenis' => 'semua']) }}"
                    class="{{ $filter === 'semua' ? 'active' : '' }}"
                >
                    Semua
                </a>

                <a
                    href="{{ route('tenant.riwayat', ['jenis' => 'offline']) }}"
                    class="{{ $filter === 'offline' ? 'active' : '' }}"
                >
                    Offline
                </a>

                <a
                    href="{{ route('tenant.riwayat', ['jenis' => 'online']) }}"
                    class="{{ $filter === 'online' ? 'active' : '' }}"
                >
                    Online
                </a>
            </div>
        </section>

        <section class="panel tenant-panel">
            <div class="panel-header">
                <div>
                    <h2>Daftar Riwayat</h2>
                    <p>Transaksi yang sudah selesai.</p>
                </div>
            </div>

            <div class="tenant-table-wrapper">
                <table class="tenant-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($riwayat as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['kode'] }}</strong>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    @if($item['jenis'] === 'offline')
                                        <span class="tenant-history-type offline">Offline</span>
                                    @else
                                        <span class="tenant-history-type online">Online</span>
                                    @endif
                                </td>

                                <td>{{ $item['jumlah_item'] }} item</td>

                                <td>
                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="status-success">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="tenant-empty-table">
                                    Belum ada riwayat transaksi.
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
    lucide.createIcons();
</script>

</body>
</html>