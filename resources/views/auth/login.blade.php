<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>

<div class="login-page">

    <section class="login-info">

        <div class="brand">
            <div class="brand-icon">K</div>

            <div>
                <h1>KantinKita</h1>
                <p>Sistem Transaksi Kantin Multi-Tenant</p>
            </div>
        </div>

        <div class="info-content">

            <span class="badge">Kantin Digital</span>

            <h2>
                Pesan makanan lebih mudah,
                cepat, dan praktis.
            </h2>

            <p class="description">
                Satu sistem yang menghubungkan mahasiswa,
                tenant, dan kasir utama dalam proses transaksi kantin.
            </p>

            <div class="features">

                <div class="feature">
                    <span>✓</span>
                    Pilih tenant dan menu dengan mudah
                </div>

                <div class="feature">
                    <span>✓</span>
                    Pemesanan dan pembayaran online
                </div>

                <div class="feature">
                    <span>✓</span>
                    Pantau status pesanan
                </div>

                <div class="feature">
                    <span>✓</span>
                    Kumpulkan poin dan tukarkan reward
                </div>

            </div>

        </div>

    </section>


    <section class="login-area">

        <div class="login-card">

            <div class="login-header">
                <span>Selamat Datang</span>

                <h2>Masuk ke KantinKita</h2>

                <p>
                    Masukkan email dan password untuk melanjutkan.
                </p>
            </div>


            <form id="loginForm">

                <div class="form-group">
                    <label for="email">Email</label>

                    <input
                        type="email"
                        id="email"
                        placeholder="Masukkan email"
                        required
                    >
                </div>


                <div class="form-group">

                    <label for="password">Password</label>

                    <div class="password-box">

                        <input
                            type="password"
                            id="password"
                            placeholder="Masukkan password"
                            required
                        >

                        <button
                            type="button"
                            id="togglePassword"
                        >
                            Lihat
                        </button>

                    </div>

                </div>


                <div class="login-options">

                    <label>
                        <input type="checkbox">
                        Ingat saya
                    </label>

                    <a href="#">
                        Lupa password?
                    </a>

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Masuk
                </button>

            </form>


            <div class="divider">
                <span></span>
                <p>atau</p>
                <span></span>
            </div>


            <div class="register">

                <p>Belum punya akun pelanggan?</p>

                <a
                    href="/register"
                    class="register-button"
                >
                    Daftar Sebagai Pelanggan
                </a>

                <small>
                    Akun tenant dan Kasir Utama/Admin sudah disediakan oleh sistem.
                </small>

            </div>

        </div>

    </section>

</div>


<script src="{{ asset('js/auth.js') }}"></script>

</body>
</html>