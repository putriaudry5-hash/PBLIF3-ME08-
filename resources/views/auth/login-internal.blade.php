<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Internal | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="auth-page">

    <!-- BAGIAN KIRI -->
    <section class="auth-intro internal-intro">

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
                AKSES INTERNAL KANTIN
            </p>

            <h2>
                Kelola operasional kantin
                <span>dalam satu sistem.</span>
            </h2>

            <p class="intro-description">
                Digunakan oleh Tenant dan Kasir Utama/Admin
                untuk mengelola pesanan, transaksi,
                pembayaran, dan laporan.
            </p>


            <div class="feature-grid">

                <div class="feature-card">
                    <span>📋</span>
                    <p>Kelola Pesanan</p>
                </div>

                <div class="feature-card">
                    <span>💳</span>
                    <p>Pantau Transaksi</p>
                </div>

                <div class="feature-card">
                    <span>📊</span>
                    <p>Lihat Laporan</p>
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

                <h3>
                    Login Tenant / Kasir Utama
                </h3>

                <p>
                    Masuk menggunakan akun yang sudah
                    didaftarkan pada sistem KantinKita.
                </p>

            </div>


            <form method="POST" action="#">

                @csrf


                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <div class="input-group">

                        <span class="input-icon">
                            ✉
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-icon">
                            🔒
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            class="password-toggle"
                            type="button"
                            onclick="togglePassword()"
                            aria-label="Tampilkan password"
                        >
                            👁
                        </button>

                    </div>

                </div>


                <div class="form-options">

                    <label class="remember-me">

                        <input
                            type="checkbox"
                            name="remember"
                        >

                        <span>Ingat saya</span>

                    </label>

                </div>


                <button
                    type="submit"
                    class="btn-login"
                >

                    Masuk

                </button>

            </form>


            <div class="info-box">

                <div class="info-symbol">
                    i
                </div>

                <div>

                    <strong>Akun internal</strong>

                    <p>
                        Akun Tenant dan Kasir Utama/Admin
                        dibuat dan dikelola oleh administrator.
                    </p>

                </div>

            </div>

        </div>

    </section>

</div>


<script src="{{ asset('js/login.js') }}"></script>

</body>
</html>