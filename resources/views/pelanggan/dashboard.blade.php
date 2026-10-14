
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Pelanggan | KantinKita</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/pelanggan.css') }}?v={{ filemtime(public_path('css/pelanggan.css')) }}"
    >
</head>

<body>

{{-- NAVBAR --}}
<header class="navbar">
    <div class="navbar-inner">

        {{-- LOGO --}}
        <a href="{{ route('pelanggan.dashboard') }}" class="logo">
            <div class="logo-box">K</div>
            <h2>Kantin<span>Kita</span></h2>
        </a>

        {{-- MENU NAVBAR --}}
        <nav class="nav-menu">
            <a href="{{ route('pelanggan.dashboard') }}" class="active">
                Beranda
            </a>

            <a href="{{ route('pelanggan.pesanan-saya') }}">
                Pesanan Saya
            </a>
        </nav>

        {{-- PROFIL PELANGGAN --}}
        <a href="{{ route('pelanggan.profil') }}" class="user-profile">
            <div class="user-avatar">E</div>

            <div class="user-info">
                <strong>Enting</strong>
                <span>Pelanggan</span>
            </div>
        </a>

    </div>
</header>


{{-- KONTEN UTAMA --}}
<main>

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-content">

            <span class="hero-label">
                SELAMAT DATANG
            </span>

            <h1>
                Mau makan apa
                <br>
                hari ini?
            </h1>

            <p>
                Pilih tenant yang kamu inginkan, lihat menu yang
                tersedia, lalu lakukan pemesanan sebelum waktu istirahat.
            </p>

            <a href="#tenant" class="hero-button">
                Lihat Tenant
            </a>

        </div>

        <div class="hero-image">
            <img
                src="{{ asset('images/tenant1.png') }}"
                alt="KantinKita"
            >

            <div class="hero-image-overlay"></div>
        </div>
    </section>


    {{-- DAFTAR TENANT --}}
    <section class="tenant-section" id="tenant">

        <div class="section-heading">
            <div>
                <span class="section-label">
                    PILIH TENANT
                </span>

                <h2>Daftar Tenant</h2>

                <p>
                    Pilih tenant untuk melihat menu yang tersedia.
                </p>
            </div>

            <span class="tenant-count">
                8 Tenant
            </span>
        </div>


        {{-- DATA TENANT --}}
        @php
            $tenants = [
                [
                    'id' => 1,
                    'nama' => 'Dapur Bu Sari',
                    'kategori' => 'Masakan Rumahan',
                    'gambar' => 'tenant1.png',
                    'jam' => '07:00 - 15:00',
                    'rating' => '4.8',
                    'pesanan' => '120+'
                ],
                [
                    'id' => 2,
                    'nama' => 'Kantin Sehat',
                    'kategori' => 'Makanan Sehat',
                    'gambar' => 'tenant2.png',
                    'jam' => '08:00 - 16:00',
                    'rating' => '4.7',
                    'pesanan' => '98+'
                ],
                [
                    'id' => 3,
                    'nama' => 'Warung Barokah',
                    'kategori' => 'Aneka Makanan',
                    'gambar' => 'tenant3.png',
                    'jam' => '07:30 - 15:30',
                    'rating' => '4.6',
                    'pesanan' => '87+'
                ],
                [
                    'id' => 4,
                    'nama' => 'Kantin Maju',
                    'kategori' => 'Mie & Makanan Ringan',
                    'gambar' => 'tenant4.png',
                    'jam' => '08:00 - 16:00',
                    'rating' => '4.5',
                    'pesanan' => '76+'
                ],
                [
                    'id' => 5,
                    'nama' => 'Resto Kampus',
                    'kategori' => 'Aneka Masakan',
                    'gambar' => 'tenant5.png',
                    'jam' => '07:00 - 16:00',
                    'rating' => '4.7',
                    'pesanan' => '105+'
                ],
                [
                    'id' => 6,
                    'nama' => 'Pojok Nusantara',
                    'kategori' => 'Masakan Nusantara',
                    'gambar' => 'tenant6.png',
                    'jam' => '07:30 - 15:00',
                    'rating' => '4.8',
                    'pesanan' => '114+'
                ],
                [
                    'id' => 7,
                    'nama' => 'Kedai Kita',
                    'kategori' => 'Minuman & Snack',
                    'gambar' => 'tenant7.png',
                    'jam' => '08:00 - 17:00',
                    'rating' => '4.6',
                    'pesanan' => '92+'
                ],
                [
                    'id' => 8,
                    'nama' => 'Dapoer Rasa',
                    'kategori' => 'Masakan Indonesia',
                    'gambar' => 'tenant8.png',
                    'jam' => '07:00 - 15:30',
                    'rating' => '4.7',
                    'pesanan' => '101+'
                ]
            ];
        @endphp


        {{-- KARTU TENANT --}}
        <div class="tenant-grid">

            @foreach ($tenants as $tenant)

                <article class="tenant-card">

                    {{-- GAMBAR TENANT --}}
                    <div class="tenant-photo">
                        <img
                            src="{{ asset('images/' . $tenant['gambar']) }}"
                            alt="{{ $tenant['nama'] }}"
                        >

                        <span class="open-badge">
                            <span class="status-dot"></span>
                            Buka
                        </span>
                    </div>


                    {{-- INFORMASI TENANT --}}
                    <div class="tenant-content">

                        <div class="tenant-title">
                            <div>
                                <h3>{{ $tenant['nama'] }}</h3>

                                <p class="category">
                                    {{ $tenant['kategori'] }}
                                </p>
                            </div>

                            <span class="rating">
                                ★ {{ $tenant['rating'] }}
                            </span>
                        </div>


                        {{-- JAM OPERASIONAL DAN PESANAN --}}
                        <div class="tenant-meta">

                            <div class="meta-item">
                                <span>Jam Operasional</span>
                                <strong>{{ $tenant['jam'] }}</strong>
                            </div>

                            <div class="meta-item">
                                <span>Pesanan</span>
                                <strong>{{ $tenant['pesanan'] }}</strong>
                            </div>

                        </div>


                        {{-- TOMBOL LIHAT MENU --}}
                        <a
                            href="{{ route('pelanggan.tenant.menu', ['tenant' => $tenant['id']]) }}"
                            class="tenant-button"
                        >
                            Lihat Menu
                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    </section>

</main>

</body>
</html>
