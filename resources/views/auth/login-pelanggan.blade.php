<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Pelanggan | KantinKita</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/login-pelanggan.css') }}?v={{ filemtime(public_path('css/login-pelanggan.css')) }}"
    >
</head>

<body>

<div class="customer-login-page">

    {{-- =====================================================
         PANEL KIRI
    ====================================================== --}}
    <section class="customer-intro">

        <div class="intro-shape intro-shape-top"></div>
        <div class="intro-shape intro-shape-bottom"></div>

        <div class="customer-brand">
            <div class="customer-brand-logo">
                K
            </div>

            <h1>
                Kantin<span>Kita</span>
            </h1>
        </div>


        <div class="customer-intro-content">

            <span class="customer-label">
                KANTIN KAMPUS DIGITAL
            </span>

            <h2>
                Pesan makanan
                <br>
                di kantin kampus

                <span>
                    lebih mudah dan praktis.
                </span>
            </h2>

            <p>
                Pilih tenant, lihat menu, lakukan pemesanan,
                dan ambil makanan ketika pesanan sudah siap.
            </p>

        </div>


        <div class="customer-footer">
            KantinKita
        </div>

    </section>


    {{-- =====================================================
         TRANSISI TENGAH
    ====================================================== --}}
    <svg
        class="customer-middle-wave"
        viewBox="0 0 180 1000"
        preserveAspectRatio="none"
        aria-hidden="true"
    >
        <defs>

            <linearGradient
                id="customerCreamFade"
                gradientUnits="userSpaceOnUse"
                x1="50"
                y1="0"
                x2="180"
                y2="0"
            >
                <stop
                    offset="0%"
                    stop-color="#EFD8BC"
                    stop-opacity="0.42"
                />

                <stop
                    offset="35%"
                    stop-color="#F5E5D1"
                    stop-opacity="0.26"
                />

                <stop
                    offset="70%"
                    stop-color="#FBF2E8"
                    stop-opacity="0.10"
                />

                <stop
                    offset="100%"
                    stop-color="#FFFDF9"
                    stop-opacity="0"
                />

            </linearGradient>

        </defs>


        {{-- BASE PUTIH --}}
        <path
            d="
                M 57 0
                C 37 190, 32 370, 37 520
                C 42 700, 58 850, 55 1000
                L 180 1000
                L 180 0
                Z
            "
            fill="#FFFDF9"
        />


        {{-- CREAM SAMAR --}}
        <path
            d="
                M 57 0
                C 37 190, 32 370, 37 520
                C 42 700, 58 850, 55 1000
                L 180 1000
                L 180 0
                Z
            "
            fill="url(#customerCreamFade)"
        />


        {{-- ORANGE --}}
        <path
            d="
                M 57 -20
                C 37 190, 32 370, 37 520
                C 42 700, 58 850, 55 1020
            "
            fill="none"
            stroke="#F7941F"
            stroke-width="26"
        />

    </svg>


    {{-- =====================================================
         PANEL KANAN
    ====================================================== --}}
    <section class="customer-form-section">

        <svg
            class="customer-decoration"
            viewBox="0 0 1000 1000"
            preserveAspectRatio="none"
            aria-hidden="true"
        >
            {{-- KANAN ATAS --}}
            <ellipse
                cx="1010"
                cy="-80"
                rx="470"
                ry="300"
                fill="#F5A052"
                fill-opacity="0.045"
            />

            <ellipse
                cx="1080"
                cy="-100"
                rx="370"
                ry="240"
                fill="#F5A052"
                fill-opacity="0.045"
            />

            {{-- KANAN BAWAH --}}
            <ellipse
                cx="760"
                cy="1130"
                rx="760"
                ry="330"
                fill="#F6A054"
                fill-opacity="0.035"
            />

            <ellipse
                cx="1080"
                cy="1110"
                rx="480"
                ry="270"
                fill="#F49B43"
                fill-opacity="0.055"
            />
        </svg>


        {{-- =================================================
             CARD LOGIN
        ================================================== --}}
        <div class="customer-login-card">

            <div class="card-brand">
                <div class="card-brand-logo">
                    K
                </div>

                <h2>
                    Kantin<span>Kita</span>
                </h2>
            </div>


            <div class="customer-heading">

                <h3>
                    Masuk sebagai Pelanggan
                </h3>

                <p>
                    Gunakan akun Google kamu untuk masuk ke KantinKita.
                </p>

            </div>


            {{-- GOOGLE LOGIN --}}
            <a
                href="{{ route('pelanggan.dashboard') }}"
                class="google-login-button"
            >

                <svg
                    class="google-logo"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        fill="#4285F4"
                        d="M21.35 11.1H12v3.18h5.38c-.23 1.5-1.72 4.39-5.38 4.39-3.24 0-5.88-2.68-5.88-5.99S8.76 6.69 12 6.69c1.85 0 3.09.79 3.8 1.46l2.59-2.52C16.72 4.08 14.56 3.14 12 3.14 6.68 3.14 2.36 7.42 2.36 12.68S6.68 22.22 12 22.22c6.92 0 9.63-4.86 9.63-7.38 0-.5-.05-.98-.14-1.42z"
                    />
                </svg>

                <span>
                    Masuk dengan Google
                </span>

            </a>


            <div class="login-divider">
                <span></span>
            </div>


            <div class="customer-info">

                <strong>
                    Tidak perlu registrasi ulang
                </strong>

                <p>
                    Gunakan akun Google yang sudah kamu miliki
                    untuk mengakses KantinKita.
                </p>

            </div>

        </div>

    </section>

</div>

</body>
</html>