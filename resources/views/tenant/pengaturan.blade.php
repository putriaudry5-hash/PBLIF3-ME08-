<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Tenant | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/tenant-pengaturan.css') }}?v={{ filemtime(public_path('css/tenant-pengaturan.css')) }}">
</head>

<body>

<div class="admin-layout">
    
@include('tenant.partials.sidebar')

    <main class="admin-main">
        <header class="topbar">
            <div class="topbar-title">
                <h1>Pengaturan</h1>
                <p>Kelola profil, akun, dan pengaturan {{ $tenant->nama_tenant }}.</p>
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
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="tenant-error-alert">
                <i data-lucide="circle-alert"></i>
                <div>
                    <strong>Data belum dapat disimpan.</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="tenant-settings-grid">
            <section class="panel tenant-setting-panel">
                <div class="panel-header">
                    <div>
                        <h2>Profil Tenant</h2>
                        <p>Informasi utama tenant.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('tenant.pengaturan.profil') }}" class="tenant-setting-form">
                    @csrf
                    @method('PUT')

                    <div class="tenant-setting-field">
                        <label>Nama Tenant</label>
                        <input type="text" name="nama_tenant" value="{{ old('nama_tenant', $tenant->nama_tenant) }}" required>
                    </div>

                    <div class="tenant-setting-field">
                        <label>Nama Penanggung Jawab</label>
                        <input type="text" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab', $tenant->nama_penanggung_jawab) }}">
                    </div>

                    <div class="tenant-setting-grid-two">
                        <div class="tenant-setting-field">
                            <label>Email Tenant</label>
                            <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required>
                        </div>

                        <div class="tenant-setting-field">
                            <label>No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $tenant->no_hp) }}">
                        </div>
                    </div>

                    <div class="tenant-setting-grid-two">
                        <div class="tenant-setting-field">
                            <label>Jenis Tenant</label>
                            <select name="jenis_tenant" required>
                                <option value="satuan" @selected($tenant->jenis_tenant === 'satuan')>Satuan</option>
                                <option value="prasmanan" @selected($tenant->jenis_tenant === 'prasmanan')>Prasmanan</option>
                            </select>
                        </div>

                        <div class="tenant-setting-field">
                            <label>Lokasi Kios</label>
                            <input type="text" name="lokasi_kios" value="{{ old('lokasi_kios', $tenant->lokasi_kios) }}">
                        </div>
                    </div>

                    <div class="tenant-setting-actions">
                        <button type="submit" class="tenant-primary-button">
                            <i data-lucide="save"></i>
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </section>

            <section class="panel tenant-setting-panel">
                <div class="panel-header">
                    <div>
                        <h2>Pesanan Online</h2>
                        <p>Aktifkan atau nonaktifkan seluruh pesanan online tenant.</p>
                    </div>
                </div>

                <div class="tenant-online-setting">
                    <div>
                        <span>Status Pesanan Online</span>
                        <strong class="{{ $tenant->aktif_online ? 'aktif' : 'nonaktif' }}">
                            {{ $tenant->aktif_online ? 'Aktif' : 'Nonaktif' }}
                        </strong>
                    </div>

                    <form method="POST" action="{{ route('tenant.pengaturan.online') }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="{{ $tenant->aktif_online ? 'tenant-disable-online' : 'tenant-enable-online' }}">
                            <i data-lucide="{{ $tenant->aktif_online ? 'wifi-off' : 'wifi' }}"></i>
                            {{ $tenant->aktif_online ? 'Nonaktifkan Online' : 'Aktifkan Online' }}
                        </button>
                    </form>
                </div>
            </section>

            <section class="panel tenant-setting-panel">
                <div class="panel-header">
                    <div>
                        <h2>Akun Login</h2>
                        <p>Data akun yang digunakan untuk masuk ke sistem.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('tenant.pengaturan.akun') }}" class="tenant-setting-form">
                    @csrf
                    @method('PUT')

                    <div class="tenant-setting-field">
                        <label>Nama Akun</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    </div>

                    <div class="tenant-setting-field">
                        <label>Email Login</label>
                        <input type="email" name="email_login" value="{{ old('email_login', $user->email) }}" required>
                    </div>

                    <div class="tenant-setting-actions">
                        <button type="submit" class="tenant-primary-button">
                            <i data-lucide="save"></i>
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </section>

            <section class="panel tenant-setting-panel">
                <div class="panel-header">
                    <div>
                        <h2>Keamanan</h2>
                        <p>Ubah password akun Tenant.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('tenant.pengaturan.password') }}" class="tenant-setting-form">
                    @csrf
                    @method('PUT')

                    <div class="tenant-setting-field">
                        <label>Password Lama</label>
                        <input type="password" name="password_lama" required>
                    </div>

                    <div class="tenant-setting-field">
                        <label>Password Baru</label>
                        <input type="password" name="password_baru" minlength="6" required>
                    </div>

                    <div class="tenant-setting-field">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" minlength="6" required>
                    </div>

                    <div class="tenant-setting-actions">
                        <button type="submit" class="tenant-primary-button">
                            <i data-lucide="key-round"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>