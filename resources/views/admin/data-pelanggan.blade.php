<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan | KantinKita</title>
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

            <a href="{{ route('admin.pelanggan') }}" class="active">
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
            <a href="{{ route('login.internal') }}">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="topbar">
            <div>
                <h1>Data Pelanggan</h1>
                <p>Kelola akun pelanggan yang terdaftar pada KantinKita.</p>
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

        @if (session('success'))
            <div class="alert-success" id="successAlert">
                {{ session('success') }}
            </div>
        @endif

        <section class="customer-summary">
            <div class="customer-summary-icon">
                <i data-lucide="users"></i>
            </div>

            <div>
                <span>Total Pelanggan</span>
                <strong>{{ count($pelanggans) }} Pelanggan Terdaftar</strong>
            </div>
        </section>

        <section class="panel customer-panel">
            <div class="customer-toolbar">
                <div>
                    <h2>Daftar Pelanggan</h2>
                    <p>Data pelanggan yang terdaftar pada KantinKita.</p>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Tanggal Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pelanggans as $pelanggan)
                            @php
                                $statusAktif = strtolower($pelanggan->status) === 'aktif';
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="customer-name-cell">
                                        <div class="customer-avatar">
                                            @if ($pelanggan->foto)
                                                <img src="{{ $pelanggan->foto }}" alt="{{ $pelanggan->nama }}">
                                            @else
                                                {{ strtoupper(substr($pelanggan->nama, 0, 2)) }}
                                            @endif
                                        </div>

                                        <strong>{{ $pelanggan->nama }}</strong>
                                    </div>
                                </td>

                                <td>{{ $pelanggan->email }}</td>

                                <td>
                                    <span class="customer-status {{ $statusAktif ? 'active' : 'inactive' }}">
                                        {{ $statusAktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional($pelanggan->created_at)->format('d/m/Y') }}
                                </td>

                                <td>
                                    <div class="customer-actions">
                                        <form
                                            action="{{ route('admin.pelanggan.status', $pelanggan->id) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            @if ($statusAktif)
                                                <button
                                                    type="button"
                                                    class="btn-disable-customer"
                                                    onclick="changeCustomerStatus(this, 'nonaktifkan')"
                                                >
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn-enable-customer"
                                                    onclick="changeCustomerStatus(this, 'aktifkan')"
                                                >
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>

                                        <form
                                            action="{{ route('admin.pelanggan.destroy', $pelanggan->id) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn-delete-customer"
                                                onclick="deleteCustomer(this)"
                                            >
                                                <i data-lucide="trash-2"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="customer-empty">
                                    Belum ada pelanggan yang terdaftar.
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
<script src="{{ asset('js/admin-pelanggan.js') }}"></script>
</body>
</html>