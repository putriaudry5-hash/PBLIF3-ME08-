<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Online Tenant | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
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
                <h1>Pesanan Online</h1>
                <p>Kelola pesanan online {{ $tenant->nama_tenant }}.</p>
            </div>

            @include('tenant.partials.profile-menu')
            
        </header>

        @if(session('success'))
            <div class="tenant-success-alert">
                <div class="tenant-success-icon">
                    <i data-lucide="circle-check"></i>
                </div>
                <div>
                    <strong>{{ session('success') }}</strong>
                    <p>Status pesanan sudah diperbarui.</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="tenant-error-alert">
                <i data-lucide="circle-alert"></i>
                <div>
                    <strong>{{ session('error') }}</strong>
                </div>
            </div>
        @endif

        <section class="tenant-online-stat-grid">
            <div class="tenant-small-stat">
                <span>Menunggu</span>
                <strong>{{ $jumlahMenunggu }}</strong>
            </div>

            <div class="tenant-small-stat">
                <span>Diproses</span>
                <strong>{{ $jumlahDiproses }}</strong>
            </div>

            <div class="tenant-small-stat">
                <span>Siap Diambil</span>
                <strong>{{ $jumlahSiap }}</strong>
            </div>

            <div class="tenant-small-stat">
                <span>Selesai</span>
                <strong>{{ $jumlahSelesai }}</strong>
            </div>
        </section>

        <section class="panel tenant-panel">
            <div class="panel-header">
                <div>
                    <h2>Daftar Pesanan Online</h2>
                    <p>Pesanan pelanggan yang masuk ke tenant Anda.</p>
                </div>
            </div>

            <div class="tenant-table-wrapper">
                <table class="tenant-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pesanans as $pesanan)
                            <tr>
                                <td>
                                    <strong>{{ $pesanan->kode_pesanan }}</strong>
                                </td>

                                <td>
                                    {{ $pesanan->pelanggan?->nama ?? 'Pelanggan' }}
                                </td>

                                <td>
                                    Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if($pesanan->status_pembayaran === 'lunas')
                                        <span class="status-success">Lunas</span>
                                    @else
                                        <span class="status-waiting">Belum Lunas</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="tenant-order-status status-{{ $pesanan->status_pesanan }}">
                                        @switch($pesanan->status_pesanan)
                                            @case('menunggu')
                                                Menunggu
                                                @break

                                            @case('diproses')
                                                Diproses
                                                @break

                                            @case('siap_diambil')
                                                Siap Diambil
                                                @break

                                            @case('selesai')
                                                Selesai
                                                @break
                                        @endswitch
                                    </span>
                                </td>

                                <td>
                                    @if($pesanan->status_pesanan !== 'selesai')
                                        <form method="POST" action="{{ route('tenant.pesanan.online.lanjut', $pesanan->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="tenant-order-button"
                                                {{ $pesanan->status_pembayaran !== 'lunas' ? 'disabled' : '' }}
                                            >
                                                @if($pesanan->status_pesanan === 'menunggu')
                                                    Proses Pesanan
                                                @elseif($pesanan->status_pesanan === 'diproses')
                                                    Siap Diambil
                                                @elseif($pesanan->status_pesanan === 'siap_diambil')
                                                    Selesaikan
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <span class="tenant-order-done">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="tenant-empty-table">
                                    Belum ada pesanan online.
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