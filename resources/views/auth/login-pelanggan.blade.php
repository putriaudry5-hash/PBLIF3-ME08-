<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Pelanggan | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="auth-page">

    <!-- BAGIAN KIRI -->
    <section class="auth-intro">

        <div class="brand">
            <div class="brand-logo">
                <span>🍴</span>
            </div>

            <h1>
                Kantin<span>Kita</span>
            </h1>
        </div>

        <div class="intro-content">

            <p class="intro-label">
                KANTIN KAMPUS DIGITAL
            </p>

            <h2>
                Pesan makanan di kantin kampus
                <span>lebih mudah dan praktis.</span>
            </h2>

            <p class="intro-description">
                Pilih tenant, lihat menu, lakukan pemesanan,
                dan ambil makanan ketika pesanan sudah siap.
            </p>

            <div class="intro-card">

                <div class="intro-card-icon">
                    🍛
                </div>

                <div>
                    <h3>Pesan dari berbagai tenant</h3>

                    <p>
                        Semua menu kantin tersedia dalam satu sistem.
                    </p>
                </div>

            </div>

        </div>

    </section>


    <!-- BAGIAN KANAN -->
    <section class="auth-form-section">

        <div class="auth-card">

            <div class="auth-card-logo">
                <span>🍴</span>

                <h2>
                    Kantin<span>Kita</span>
                </h2>
            </div>

            <div class="auth-heading">

                <h3>Masuk sebagai Pelanggan</h3>

                <p>
                    Gunakan akun Google yang sudah kamu miliki
                    untuk langsung masuk.
                </p>

            </div>


            <!-- GOOGLE LOGIN -->
            <a href="{{ route('pelanggan.dashboard') }}" class="btn-google">

                <svg
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >

                    <path
                        fill="#4285F4"
                        d="M21.35 11.1H12v3.18h5.38c-.23 1.5-1.72 4.39-5.38 4.39-3.24 0-5.88-2.68-5.88-5.99S8.76 6.69 12 6.69c1.85 0 3.09.79 3.8 1.46l2.59-2.52C16.72 4.08 14.56 3.14 12 3.14 6.68 3.14 2.36 7.42 2.36 12.68S6.68 22.22 12 22.22c6.92 0 9.63-4.86 9.63-7.38 0-.5-.05-.98-.14-1.42z"
                    />

                </svg>

                <span>Masuk dengan Google</span>

            </a>


            <div class="info-box">

                <div class="info-symbol">
                    i
                </div>

                <div>
                    <strong>Tidak perlu registrasi ulang</strong>

                    <p>
                        Cukup gunakan akun Google yang sudah kamu miliki.
                    </p>
                </div>

            </div>

        </div>

    </section>

</div>

</body>
</html>