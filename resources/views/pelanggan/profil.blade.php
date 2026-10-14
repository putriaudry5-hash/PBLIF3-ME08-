
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - KantinKita</title>

    <link rel="stylesheet"
          href="{{ asset('css/profil-pelanggan.css') }}?v={{ filemtime(public_path('css/profil-pelanggan.css')) }}">
</head>
<body>

    {{-- TOPBAR --}}
    <header class="topbar">
        <a href="{{ route('pelanggan.dashboard') }}" class="brand">
            Kantin<span>Kita</span>
        </a>

        <div class="profile-photo">
            <img
                src="{{ asset('storage/foto-profil/foto.jpg') }}"
                alt="Foto Profil"
                onerror="this.style.display='none'; this.parentElement.textContent='E';"
            >
        </div>
    </header>

    {{-- KONTEN PROFIL --}}
    <main class="container">

        <a href="{{ route('pelanggan.dashboard') }}" class="back">
            ← Kembali ke Beranda
        </a>

        {{-- JUDUL HALAMAN --}}
        <div class="page-header">
            <h1>Profil Saya</h1>
            <p>Kelola informasi akun dan lihat riwayat pesanan Anda di sini.</p>
        </div>

        {{-- INFORMASI PROFIL --}}
        <section class="profile-info">
            <div class="profile-photo">E</div>

            <div class="identity">
                <h3>Enting Renta</h3>
                <p>enting@gmail.com</p>
                <span class="role">♟ Pelanggan</span>
            </div>
        </section>

        {{-- MENU PROFIL --}}
        <nav class="profile-menu">

            {{-- DATA DIRI --}}
            <a href="{{ route('pelanggan.data-diri') }}" class="menu-item">
                <div class="menu-icon">♟</div>

                <div class="menu-text">
                    <strong>Data Diri</strong>
                    <span>Lihat dan ubah informasi pribadi Anda</span>
                </div>

                <span class="arrow">›</span>
            </a>

            {{-- RIWAYAT PESANAN --}}
            <a href="{{ route('pelanggan.riwayat-transaksi') }}" class="menu-item">
                <div class="menu-icon">▤</div>

                <div class="menu-text">
                    <strong>Riwayat Pesanan</strong>
                    <span>Lihat daftar pesanan dan transaksi Anda</span>
                </div>

                <span class="arrow">›</span>
            </a>

        </nav>

        {{-- KELUAR --}}
        <div class="logout-section">
            <a href="{{ route('login.pelanggan') }}" class="logout-link">
                <div class="menu-icon">↪</div>

                <div class="menu-text">
                    <strong>Keluar</strong>
                    <span>Keluar dari akun Anda</span>
                </div>

                <span class="arrow">›</span>
            </a>
        </div>

    </main>

</body>
</html>
