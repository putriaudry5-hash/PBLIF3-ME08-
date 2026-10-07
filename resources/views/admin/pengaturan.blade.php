<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan | KantinKita</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-pengaturan.css') }}">
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

            <a href="{{ route('admin.riwayat') }}">
                <i data-lucide="history"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <a href="{{ route('admin.laporan') }}">
                <i data-lucide="chart-no-axes-combined"></i>
                <span>Laporan</span>
            </a>

            <a
                href="{{ route('admin.pengaturan') }}"
                class="active"
            >
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
        
    </aside>

    <main class="admin-main">

        <header class="topbar">
            <div>
                <h1>Pengaturan</h1>
                <p>Kelola informasi kantin dan akun Kasir Utama/Admin.</p>
            </div>

            @include('admin.partials.profile-menu')

        </header>

        @if (session('success'))
            <div
                class="setting-alert-success"
                id="settingAlert"
            >
                <i data-lucide="circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('success_password'))
            <div
                class="setting-alert-success"
                id="passwordAlert"
            >
                <i data-lucide="circle-check"></i>
                <span>{{ session('success_password') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="setting-alert-error">
                <i data-lucide="circle-alert"></i>

                <div>
                    <strong>Ada data yang perlu diperbaiki.</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.pengaturan.update') }}"
            class="setting-form"
        >
            @csrf
            @method('PUT')

            <section class="panel setting-panel">

                <div class="setting-panel-header">
                    <div class="setting-header-icon">
                        <i data-lucide="store"></i>
                    </div>

                    <div>
                        <h2>Informasi Kantin</h2>
                        <p>Atur nama dan jam operasional kantin.</p>
                    </div>
                </div>

                <div class="setting-panel-body">

                    <div class="setting-group setting-full">
                        <label for="namaKantin">
                            Nama Kantin
                        </label>

                        <input
                            type="text"
                            id="namaKantin"
                            name="nama_kantin"
                            value="{{ old(
                                'nama_kantin',
                                $setting->nama_kantin
                            ) }}"
                            required
                        >
                    </div>

                    <div class="setting-grid">

                        <div class="setting-group">
                            <label for="jamBuka">
                                Jam Buka
                            </label>

                            <input
                                type="time"
                                id="jamBuka"
                                name="jam_buka"
                                value="{{ old(
                                    'jam_buka',
                                    $setting->jam_buka
                                ) }}"
                            >

                            <small>
                                Waktu mulai operasional kantin.
                            </small>
                        </div>

                        <div class="setting-group">
                            <label for="jamTutup">
                                Jam Tutup
                            </label>

                            <input
                                type="time"
                                id="jamTutup"
                                name="jam_tutup"
                                value="{{ old(
                                    'jam_tutup',
                                    $setting->jam_tutup
                                ) }}"
                            >

                            <small>
                                Waktu berakhir operasional kantin.
                            </small>
                        </div>

                    </div>

                </div>
            </section>

            <section class="panel setting-panel">

                <div class="setting-panel-header">
                    <div class="setting-header-icon orange">
                        <i data-lucide="user-round"></i>
                    </div>

                    <div>
                        <h2>Profil Kasir Utama/Admin</h2>
                        <p>Informasi akun Admin yang digunakan untuk login.</p>
                    </div>
                </div>

                <div class="setting-panel-body">

                    <div class="setting-grid">

                        <div class="setting-group">
                            <label for="namaAdmin">
                                Nama Admin
                            </label>

                            <input
                                type="text"
                                id="namaAdmin"
                                name="nama_admin"
                                value="{{ old(
                                    'nama_admin',
                                    $admin->nama
                                ) }}"
                                required
                            >
                        </div>

                        <div class="setting-group">
                            <label for="emailAdmin">
                                Email Admin
                            </label>

                            <input
                                type="email"
                                id="emailAdmin"
                                name="email_admin"
                                value="{{ old(
                                    'email_admin',
                                    $admin->email
                                ) }}"
                                required
                            >
                        </div>

                    </div>

                </div>
            </section>

            <div class="setting-save-bar">

                <div class="setting-save-info">
                    <i data-lucide="info"></i>

                    <span>
                        Simpan perubahan informasi kantin
                        dan profil Admin.
                    </span>
                </div>

                <button
                    type="submit"
                    class="setting-save-button"
                >
                    <i data-lucide="save"></i>
                    Simpan Pengaturan
                </button>

            </div>

        </form>

        <form
            method="POST"
            action="{{ route('admin.pengaturan.password') }}"
            class="setting-password-form"
        >
            @csrf
            @method('PUT')

            <section class="panel setting-panel setting-password-panel">

                <div class="setting-panel-header">
                    <div class="setting-header-icon security">
                        <i data-lucide="shield-check"></i>
                    </div>

                    <div>
                        <h2>Keamanan Akun</h2>
                        <p>Ubah password akun Kasir Utama/Admin.</p>
                    </div>
                </div>

                <div class="setting-panel-body">

                    <div class="setting-group setting-full">
                        <label for="passwordLama">
                            Password Lama
                        </label>

                        <div class="setting-password-input">
                            <input
                                type="password"
                                id="passwordLama"
                                name="password_lama"
                                placeholder="Masukkan password lama"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="setting-password-toggle"
                                data-target="passwordLama"
                                aria-label="Tampilkan password"
                            >
                                <i data-lucide="eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="setting-grid">

                        <div class="setting-group">
                            <label for="passwordBaru">
                                Password Baru
                            </label>

                            <div class="setting-password-input">
                                <input
                                    type="password"
                                    id="passwordBaru"
                                    name="password"
                                    placeholder="Minimal 6 karakter"
                                    autocomplete="new-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="setting-password-toggle"
                                    data-target="passwordBaru"
                                    aria-label="Tampilkan password"
                                >
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="setting-group">
                            <label for="passwordKonfirmasi">
                                Konfirmasi Password Baru
                            </label>

                            <div class="setting-password-input">
                                <input
                                    type="password"
                                    id="passwordKonfirmasi"
                                    name="password_confirmation"
                                    placeholder="Ulangi password baru"
                                    autocomplete="new-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="setting-password-toggle"
                                    data-target="passwordKonfirmasi"
                                    aria-label="Tampilkan password"
                                >
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="setting-password-footer">

                        <div class="setting-save-info">
                            <i data-lucide="lock-keyhole"></i>

                            <span>
                                Password baru minimal 6 karakter
                                dan berbeda dari password lama.
                            </span>
                        </div>

                        <button
                            type="submit"
                            class="setting-password-button"
                        >
                            <i data-lucide="key-round"></i>
                            Ubah Password
                        </button>

                    </div>

                </div>
            </section>

        </form>

    </main>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('js/admin-pengaturan.js') }}"></script>
</body>
</html>