<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Internal | KantinKita</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/login.css') }}?v={{ filemtime(public_path('css/login.css')) }}"
    >
</head>

<body>

<div class="auth-page">

    {{-- =====================================================
         PANEL KIRI
    ====================================================== --}}
    <section class="auth-intro">

        <div class="left-shape left-shape-top"></div>
        <div class="left-shape left-shape-bottom"></div>

        <div class="brand">
            <div class="brand-logo">
                K
            </div>

            <h1>
                Kantin<span>Kita</span>
            </h1>
        </div>

        <div class="intro-content">

            <h2>
                Kelola operasional
                <br>
                kantin

                <span>
                    dengan lebih mudah.
                </span>
            </h2>

            <p>
                Sistem internal untuk Tenant dan Kasir Utama/Admin
                dalam mengelola menu, transaksi, pesanan, dan laporan.
            </p>

        </div>

        <div class="intro-footer">
            KantinKita
        </div>

    </section>


    {{-- =====================================================
         TRANSISI TENGAH
    ====================================================== --}}
    <svg
        class="middle-wave"
        viewBox="0 0 220 1000"
        preserveAspectRatio="none"
        shape-rendering="geometricPrecision"
        aria-hidden="true"
    >

        <defs>

            {{-- Cream yang makin ke kanan makin hilang --}}
            <linearGradient
                id="middleCreamFade"
                gradientUnits="userSpaceOnUse"
                x1="55"
                y1="0"
                x2="220"
                y2="0"
            >

                <stop
                    offset="0%"
                    stop-color="#EFD6B9"
                    stop-opacity="0.46"
                />

                <stop
                    offset="22%"
                    stop-color="#F3DFCA"
                    stop-opacity="0.34"
                />

                <stop
                    offset="48%"
                    stop-color="#F7E9D8"
                    stop-opacity="0.22"
                />

                <stop
                    offset="72%"
                    stop-color="#FBF2E8"
                    stop-opacity="0.11"
                />

                <stop
                    offset="90%"
                    stop-color="#FEF9F3"
                    stop-opacity="0.04"
                />

                <stop
                    offset="100%"
                    stop-color="#FFFDF9"
                    stop-opacity="0"
                />

            </linearGradient>

        </defs>


        {{-- =================================================
             BASE PUTIH

             Base ini penting supaya gradient cream tidak
             transparan langsung ke navy.
        ================================================== --}}
        <path
            d="
                M 60 0

                C 38 180,
                  34 355,
                  39 520

                C 44 690,
                  61 845,
                  57 1000

                L 220 1000
                L 220 0

                Z
            "
            fill="#FFFDF9"
        />


        {{-- =================================================
             CREAM FADE

             Mulai terlihat setelah orange lalu makin samar.
        ================================================== --}}
        <path
            d="
                M 60 0

                C 38 180,
                  34 355,
                  39 520

                C 44 690,
                  61 845,
                  57 1000

                L 220 1000
                L 220 0

                Z
            "
            fill="url(#middleCreamFade)"
        />


        {{-- =================================================
             ORANGE

             Pakai STROKE, bukan shape dua sisi.
             Jadi tebal orange selalu rata sepanjang kurva.
        ================================================== --}}
        <path
            d="
                M 60 -20

                C 38 180,
                  34 355,
                  39 520

                C 44 690,
                  61 845,
                  57 1020
            "
            fill="none"
            stroke="#F79A22"
            stroke-width="28"
            stroke-linecap="butt"
            stroke-linejoin="round"
        />

    </svg>


    {{-- =====================================================
         PANEL KANAN
    ====================================================== --}}
    <section class="auth-form-section">

        {{-- =================================================
             DEKORASI KANAN
        ================================================== --}}
        <svg
            class="right-decoration"
            viewBox="0 0 1000 1000"
            preserveAspectRatio="none"
            aria-hidden="true"
        >

            {{-- KANAN ATAS --}}
            <ellipse
                cx="955"
                cy="-35"
                rx="370"
                ry="250"
                fill="#F5A052"
                fill-opacity="0.065"
            />

            <ellipse
                cx="1050"
                cy="-70"
                rx="500"
                ry="330"
                fill="#F5A052"
                fill-opacity="0.032"
            />


            {{-- KANAN BAWAH BELAKANG --}}
            <ellipse
                cx="670"
                cy="1115"
                rx="800"
                ry="330"
                fill="#F6A054"
                fill-opacity="0.040"
            />


            {{-- KANAN BAWAH TENGAH --}}
            <ellipse
                cx="930"
                cy="1090"
                rx="580"
                ry="285"
                fill="#F49B43"
                fill-opacity="0.052"
            />


            {{-- KANAN BAWAH DEPAN --}}
            <ellipse
                cx="1055"
                cy="1115"
                rx="430"
                ry="250"
                fill="#F49B43"
                fill-opacity="0.065"
            />

        </svg>


        {{-- =================================================
             CARD LOGIN
        ================================================== --}}
        <div class="auth-card">

            <div class="auth-heading">

                <h2>
                    Login Internal
                </h2>

                <p>
                    Masuk sebagai Tenant atau Kasir Utama/Admin.
                </p>

            </div>


            @if (session('login_error'))

                <div class="login-alert">

                    <strong>
                        Login gagal
                    </strong>

                    <span>
                        {{ session('login_error') }}
                    </span>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('login.internal.submit') }}"
                autocomplete="off"
            >
                @csrf


                {{-- EMAIL --}}
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        autocomplete="off"
                        autocapitalize="none"
                        spellcheck="false"
                        required
                    >

                </div>


                {{-- PASSWORD --}}
                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="password-field">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                        >
                            Lihat
                        </button>

                    </div>

                </div>


                {{-- INGAT SAYA --}}
                <div class="form-options">

                    <label class="remember-me">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>
                            Ingat saya
                        </span>

                    </label>

                </div>


                <button
                    type="submit"
                    class="btn-login"
                >
                    Masuk
                </button>

            </form>

        </div>

    </section>

</div>


<script
    src="{{ asset('js/login.js') }}?v={{ filemtime(public_path('js/login.js')) }}"
></script>

</body>
</html>