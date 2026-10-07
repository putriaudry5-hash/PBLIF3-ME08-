<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tenant | KantinKita</title>
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

            <a href="{{ route('admin.tenant') }}" class="active">
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

    </aside>

    <main class="admin-main">
        <header class="topbar">
            <div>
                <h1>Data Tenant</h1>
                <p>Kelola tenant yang terdaftar pada KantinKita.</p>
            </div>

            @include('admin.partials.profile-menu')

        </header>

        @if (session('success'))
            <div class="alert-success" id="successAlert">
                {{ session('success') }}
            </div>
        @endif

        <section class="tenant-summary">
            <div class="tenant-summary-left">
                <div class="tenant-summary-icon">
                    <i data-lucide="store"></i>
                </div>

                <div>
                    <span>Total Tenant</span>
                    <strong>{{ count($tenants) }} Tenant Terdaftar</strong>
                </div>
            </div>

            <button type="button" class="btn-add-tenant" onclick="openTenantModal()">
                <i data-lucide="plus"></i>
                Tambah Tenant
            </button>
        </section>

        <section class="panel tenant-panel">
            <div class="tenant-toolbar">
                <div>
                    <h2>Daftar Tenant</h2>
                    <p>Informasi tenant yang dikelola oleh Kasir Utama/Admin.</p>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="tenant-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tenant</th>
                            <th>Jenis</th>
                            <th>Penanggung Jawab</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tenants as $tenant)
                            @php
                                $statusAktif = strtolower($tenant->status) === 'aktif';
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="tenant-name-cell">
                                        <div class="tenant-avatar">
                                            {{ strtoupper(substr($tenant->nama_tenant, 0, 2)) }}
                                        </div>

                                        <strong>{{ $tenant->nama_tenant }}</strong>
                                    </div>
                                </td>

                                <td>
                                    <span class="tenant-type">
                                        {{ $tenant->jenis_tenant === 'prasmanan' ? 'Prasmanan' : 'Menu Satuan' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $tenant->nama_penanggung_jawab ?: '-' }}
                                </td>

                                <td>
                                    <div class="tenant-contact">
                                        <span>{{ $tenant->email }}</span>
                                        <small>{{ $tenant->no_hp ?: '-' }}</small>
                                    </div>
                                </td>

                                <td>
                                    <span class="tenant-status {{ $statusAktif ? 'active' : 'inactive' }}">
                                        {{ $statusAktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="tenant-actions">
                                        <button
                                            type="button"
                                            class="btn-detail-tenant"
                                            data-name="{{ $tenant->nama_tenant }}"
                                            data-owner="{{ $tenant->nama_penanggung_jawab }}"
                                            data-email="{{ $tenant->email }}"
                                            data-phone="{{ $tenant->no_hp }}"
                                            data-type="{{ $tenant->jenis_tenant }}"
                                            data-location="{{ $tenant->lokasi_kios }}"
                                            data-status="{{ $tenant->status }}"
                                            data-created="{{ optional($tenant->created_at)->format('d/m/Y') }}"
                                            onclick="openDetailTenant(this)"
                                        >
                                            Detail
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-edit-tenant"
                                            data-id="{{ $tenant->id }}"
                                            data-name="{{ $tenant->nama_tenant }}"
                                            data-owner="{{ $tenant->nama_penanggung_jawab }}"
                                            data-email="{{ $tenant->email }}"
                                            data-phone="{{ $tenant->no_hp }}"
                                            data-type="{{ $tenant->jenis_tenant }}"
                                            data-location="{{ $tenant->lokasi_kios }}"
                                            onclick="openEditTenant(this)"
                                        >
                                            <i data-lucide="pencil"></i>
                                            Edit
                                        </button>

                                        <form
                                            action="{{ route('admin.tenant.status', $tenant->id) }}"
                                            method="POST"
                                            class="status-form"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            @if ($statusAktif)
                                                <button
                                                    type="button"
                                                    class="btn-disable-tenant"
                                                    onclick="changeTenantStatus(this, 'nonaktifkan')"
                                                >
                                                    Nonaktifkan
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn-enable-tenant"
                                                    onclick="changeTenantStatus(this, 'aktifkan')"
                                                >
                                                    Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="tenant-empty">
                                    Belum ada tenant yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<div class="tenant-modal" id="tenantModal">
    <div class="tenant-modal-overlay" onclick="closeTenantModal()"></div>

    <div class="tenant-modal-card">
        <div class="tenant-modal-header">
            <div>
                <h2>Tambah Tenant</h2>
                <p>Buat akun baru untuk tenant kantin.</p>
            </div>

            <button type="button" class="modal-close" onclick="closeTenantModal()">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form
    class="tenant-form"
    method="POST"
    action="{{ route('admin.tenant.store') }}"
    autocomplete="off"
>
    @csrf

    <div class="tenant-form-group">
        <label for="tenantName">Nama Tenant</label>
        <input
            type="text"
            id="tenantName"
            name="nama_tenant"
            value="{{ old('nama_tenant') }}"
            placeholder="Contoh: Dapur Bu Sari"
            autocomplete="off"
            required
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantOwner">Nama Penanggung Jawab</label>
        <input
            type="text"
            id="tenantOwner"
            name="nama_penanggung_jawab"
            value="{{ old('nama_penanggung_jawab') }}"
            placeholder="Nama pemilik atau penjaga tenant"
            autocomplete="off"
            required
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantEmail">Email</label>
        <input
            type="email"
            id="tenantEmail"
            name="email"
            value="{{ old('email') }}"
            placeholder="tenant@kantinkita.com"
            autocomplete="off"
            data-lpignore="true"
            required
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantPhone">No. HP</label>
        <input
            type="text"
            id="tenantPhone"
            name="no_hp"
            value="{{ old('no_hp') }}"
            placeholder="08xxxxxxxxxx"
            autocomplete="off"
            required
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantType">Jenis Tenant</label>
        <select id="tenantType" name="jenis_tenant" required>
            <option value="satuan" {{ old('jenis_tenant') === 'satuan' ? 'selected' : '' }}>
                Menu Satuan
            </option>
            <option value="prasmanan" {{ old('jenis_tenant') === 'prasmanan' ? 'selected' : '' }}>
                Prasmanan
            </option>
        </select>
    </div>

    <div class="tenant-form-group">
        <label for="tenantLocation">Lokasi / Nomor Kios</label>
        <input
            type="text"
            id="tenantLocation"
            name="lokasi_kios"
            value="{{ old('lokasi_kios') }}"
            placeholder="Contoh: Kios 03"
            autocomplete="off"
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantPassword">Password</label>
        <input
            type="password"
            id="tenantPassword"
            name="password"
            placeholder="Minimal 6 karakter"
            autocomplete="new-password"
            data-lpignore="true"
            required
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantConfirmPassword">Konfirmasi Password</label>
        <input
            type="password"
            id="tenantConfirmPassword"
            name="password_confirmation"
            placeholder="Masukkan kembali password"
            autocomplete="new-password"
            data-lpignore="true"
            required
        >
    </div>

    <div class="tenant-form-group">
        <label for="tenantStatus">Status Akun</label>
        <select id="tenantStatus" name="status" required>
            <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                Nonaktif
            </option>
        </select>
    </div>

    @if ($errors->any())
        <div class="form-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="tenant-modal-actions">
        <button
            type="button"
            class="btn-modal-cancel"
            onclick="closeTenantModal()"
        >
            Batal
        </button>

        <button type="submit" class="btn-modal-save">
            Simpan Tenant
        </button>
    </div>
</form>
    </div>
</div>

<div class="tenant-modal" id="editTenantModal">
    <div class="tenant-modal-overlay" onclick="closeEditTenant()"></div>

    <div class="tenant-modal-card">
        <div class="tenant-modal-header">
            <div>
                <h2>Edit Tenant</h2>
                <p>Ubah informasi tenant.</p>
            </div>

            <button type="button" class="modal-close" onclick="closeEditTenant()">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form id="editTenantForm" class="tenant-form" method="POST">
            @csrf
            @method('PUT')

            <div class="tenant-form-group">
                <label for="editTenantName">Nama Tenant</label>
                <input
                    type="text"
                    id="editTenantName"
                    name="nama_tenant"
                    required
                >
            </div>

            <div class="tenant-form-group">
                <label for="editTenantOwner">Nama Penanggung Jawab</label>
                <input
                    type="text"
                    id="editTenantOwner"
                    name="nama_penanggung_jawab"
                    required
                >
            </div>

            <div class="tenant-form-group">
                <label for="editTenantEmail">Email</label>
                <input
                    type="email"
                    id="editTenantEmail"
                    name="email"
                    required
                >
            </div>

            <div class="tenant-form-group">
                <label for="editTenantPhone">No. HP</label>
                <input
                    type="text"
                    id="editTenantPhone"
                    name="no_hp"
                    required
                >
            </div>

            <div class="tenant-form-group">
                <label for="editTenantType">Jenis Tenant</label>
                <select id="editTenantType" name="jenis_tenant" required>
                    <option value="satuan">Menu Satuan</option>
                    <option value="prasmanan">Prasmanan</option>
                </select>
            </div>

            <div class="tenant-form-group">
                <label for="editTenantLocation">Lokasi / Nomor Kios</label>
                <input
                    type="text"
                    id="editTenantLocation"
                    name="lokasi_kios"
                >
            </div>

            <div class="tenant-modal-actions">
                <button
                    type="button"
                    class="btn-modal-cancel"
                    onclick="closeEditTenant()"
                >
                    Batal
                </button>

                <button type="submit" class="btn-modal-save">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="tenant-modal" id="detailTenantModal">
    <div class="tenant-modal-overlay" onclick="closeDetailTenant()"></div>

    <div class="tenant-modal-card">
        <div class="tenant-modal-header">
            <div>
                <h2>Detail Tenant</h2>
                <p>Informasi lengkap tenant kantin.</p>
            </div>

            <button type="button" class="modal-close" onclick="closeDetailTenant()">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="tenant-detail-list">
            <div class="tenant-detail-item">
                <span>Nama Tenant</span>
                <strong id="detailTenantName">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>Penanggung Jawab</span>
                <strong id="detailTenantOwner">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>Jenis Tenant</span>
                <strong id="detailTenantType">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>Email</span>
                <strong id="detailTenantEmail">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>No. HP</span>
                <strong id="detailTenantPhone">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>Lokasi Kios</span>
                <strong id="detailTenantLocation">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>Status</span>
                <strong id="detailTenantStatus">-</strong>
            </div>

            <div class="tenant-detail-item">
                <span>Tanggal Bergabung</span>
                <strong id="detailTenantCreated">-</strong>
            </div>
        </div>

        <div class="tenant-modal-actions">
            <button
                type="button"
                class="btn-modal-cancel"
                onclick="closeDetailTenant()"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('js/admin-tenant.js') }}"></script>

@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    openTenantModal();
});
</script>
@endif

</body>
</html>