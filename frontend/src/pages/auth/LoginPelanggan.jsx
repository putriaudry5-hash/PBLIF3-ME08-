import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { GoogleLogin } from "@react-oauth/google";
import { API_URL } from "../../config";
import "../../styles/LoginPelanggan.css";

function LogoKantin({ small = false }) {
  return (
    <div className={`kantin-logo ${small ? "logo-small" : ""}`}>
      <div className="logo-box">K</div>

      <div className="logo-text">
        Kantin<span>Kita</span>
      </div>
    </div>
  );
}

function IconToko() {
  return (
    <svg
      viewBox="0 0 32 32"
      fill="none"
      stroke="currentColor"
      strokeWidth="2.2"
      strokeLinecap="round"
      strokeLinejoin="round"
      aria-hidden="true"
    >
      <path d="M5 13h22l-2-7H7l-2 7Z" />
      <path d="M7 13v13h18V13" />
      <path d="M12 26v-8h8v8" />
      <path d="M5 13c0 3 5 3 5 0 0 3 6 3 6 0 0 3 6 3 6 0 0 3 5 3 5 0" />
    </svg>
  );
}

function IconGrafik() {
  return (
    <svg
      viewBox="0 0 32 32"
      fill="none"
      stroke="currentColor"
      strokeWidth="2.3"
      strokeLinecap="round"
      strokeLinejoin="round"
      aria-hidden="true"
    >
      <path d="M5 26h23" />
      <path d="M8 24V16h5v8" />
      <path d="M16 24V7h5v17" />
      <path d="M24 24V12h4v12" />
    </svg>
  );
}

function LoginPelanggan() {
  const navigate = useNavigate();

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  // Mengirim kredensial Google ke backend Laravel
  const handleLoginSuccess = async (response) => {
    if (!response.credential) {
      setError("Kredensial Google tidak ditemukan. Silakan coba lagi.");
      return;
    }

    setLoading(true);
    setError("");

    try {
      const result = await fetch(`${API_URL}/auth/google`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          credential: response.credential,
        }),
      });

      // Membaca respons server
      const data = await result.json();

      if (!result.ok) {
        throw new Error(
          data.message || "Login Google gagal. Silakan coba lagi."
        );
      }

      // Memastikan backend mengirim token dan data pengguna
      if (!data.token || !data.user) {
        throw new Error("Data login dari server tidak lengkap.");
      }

      // Menyimpan token dan informasi pelanggan
      localStorage.setItem("kantinkita_token", data.token);
      localStorage.setItem(
        "kantinkita_user",
        JSON.stringify(data.user)
      );

      // Masuk ke halaman beranda setelah login berhasil
      navigate("/beranda", { replace: true });
    } catch (err) {
      console.error("Login Google error:", err);

      setError(
        err.message ||
          "Tidak dapat terhubung ke server Laravel. Pastikan backend berjalan."
      );
    } finally {
      setLoading(false);
    }
  };

  // Jika proses login Google gagal atau dibatalkan
  const handleLoginError = () => {
    setError("Login Google gagal atau dibatalkan. Silakan coba lagi.");
  };

  return (
    <main className="login-page">
      {/* Dekorasi latar */}
      <div className="decoration decoration-left" />
      <div className="decoration decoration-top" />
      <div className="decoration decoration-top-inner" />

      <div className="red-dot dot-top" />
      <div className="yellow-dot dot-right" />
      <div className="yellow-dot dot-left" />

      {/* Header logo */}
      <header className="login-header">
        <LogoKantin />
      </header>

      {/* Konten utama */}
      <section className="login-layout">
        {/* Bagian kiri */}
        <div className="hero-content">
          <p className="hero-label">
            SISTEM TRANSAKSI KANTIN
          </p>

          <h1 className="hero-title">
            Pesan makanan
            <br />
            lebih mudah
            <br />
            <span>dan praktis.</span>
          </h1>

          <p className="hero-description">
            Temukan berbagai pilihan menu dari tenant kantin
            kampus dalam satu platform.
            <br className="desktop-break" />
            Pesan makanan favoritmu dengan lebih praktis
            tanpa perlu antre.
          </p>

          {/* Keunggulan */}
          <div className="feature-list">
            <div className="feature-item">
              <div className="feature-icon">
                <IconToko />
              </div>

              <div className="feature-text">
                <h3>Pilihan Menu Beragam</h3>
                <p>
                  Jelajahi menu dari berbagai kantin
                </p>
              </div>
            </div>

            <div className="feature-divider" />

            <div className="feature-item">
              <div className="feature-icon">
                <IconGrafik />
              </div>

              <div className="feature-text">
                <h3>Pemesanan Lebih Praktis</h3>
                <p>
                  Pesan makanan dengan mudah secara online
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Bagian kanan: kartu login */}
        <div className="login-card">
          <LogoKantin small />

          <div className="login-card-content">
            <h2>Selamat Datang</h2>

            <p className="login-intro">
              Masuk menggunakan akun Google untuk
              menjelajahi dan memesan menu favoritmu.
            </p>

            {/* Tombol login Google asli */}
            <div className="google-login-wrapper">
              {loading ? (
                <button
                  type="button"
                  className="google-login-button"
                  disabled
                >
                  Sedang memverifikasi akun...
                </button>
              ) : (
                <GoogleLogin
                  onSuccess={handleLoginSuccess}
                  onError={handleLoginError}
                  text="signin_with"
                  shape="rectangular"
                  theme="outline"
                  size="large"
                  width="320"
                  logo_alignment="left"
                />
              )}
            </div>

            {/* Pesan error */}
            {error && (
              <p
                role="alert"
                style={{
                  color: "#b42318",
                  fontSize: "13px",
                  lineHeight: 1.5,
                  marginTop: "12px",
                  textAlign: "center",
                  overflowWrap: "anywhere",
                }}
              >
                {error}
              </p>
            )}

            <p className="login-note">
              Dengan masuk, kamu dapat melihat pilihan menu,
              melakukan pemesanan, dan memantau status
              pesananmu.
            </p>
          </div>

          <footer className="card-footer">
            Sistem Transaksi Terintegrasi untuk Kantin Multi-Tenant
          </footer>
        </div>
      </section>

      {/* Dekorasi bawah */}
      <div className="bottom-wave bottom-wave-yellow" />
      <div className="bottom-wave bottom-wave-maroon" />
      <div className="bottom-wave-line" />

      {/* Ilustrasi sederhana kantin */}
      <div className="stall-decoration" aria-hidden="true">
        <svg viewBox="0 0 200 190" fill="none">
          <g
            stroke="currentColor"
            strokeWidth="3"
            strokeLinecap="round"
            strokeLinejoin="round"
          >
            <path d="M40 70 100 28l60 42" />
            <path d="M52 70h96v92H52z" />
            <path d="M35 70h130" />
            <path d="M100 28 78 70" />
            <path d="M100 28 124 70" />
            <path d="M91 110v40" />
            <path d="M111 110v40" />
            <path d="M131 110v40" />
            <path d="M85 110v12c0 9 12 9 12 0v-12" />
            <path d="M105 110v12c0 9 12 9 12 0v-12" />
            <path d="M125 110v12c0 9 12 9 12 0v-12" />
            <path d="M52 162h96" />
          </g>

          <path
            d="M18 160c8-14 23-14 31-2 13-3 22 6 23 16H18c-7-4-7-10 0-14Z"
            fill="currentColor"
            opacity=".25"
          />
        </svg>
      </div>

      <div className="copyright">
        © 2026 KantinKita
      </div>
    </main>
  );
}

export default LoginPelanggan;