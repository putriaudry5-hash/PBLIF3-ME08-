import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { loginInternal } from "../../services/auth.js";
import "../../styles/LoginInternal.css";

/* ==========================================
   LOGO KANTINKITA
========================================== */

function Logo({ small = false }) {
  return (
    <div className={`kk-logo ${small ? "kk-logo-small" : ""}`}>
      <div className="kk-logo-icon">K</div>

      <div className="kk-logo-text">
        Kantin<span>Kita</span>
      </div>
    </div>
  );
}

/* ==========================================
   ICON EMAIL
========================================== */

function MailIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <rect
        x="3.5"
        y="5.5"
        width="17"
        height="13"
        rx="2"
        stroke="currentColor"
        strokeWidth="1.8"
      />

      <path
        d="M4.5 7L12 13L19.5 7"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}

/* ==========================================
   ICON PASSWORD
========================================== */

function LockIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <rect
        x="5"
        y="10"
        width="14"
        height="11"
        rx="2"
        stroke="currentColor"
        strokeWidth="1.8"
      />

      <path
        d="M8 10V7a4 4 0 018 0v3"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinecap="round"
      />

      <path
        d="M12 14v3"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinecap="round"
      />
    </svg>
  );
}

/* ==========================================
   ICON MANAJEMEN TENANT
========================================== */

function StoreIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path
        d="M4 10L6 4H18L20 10"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinejoin="round"
      />

      <path
        d="M4 10C4 13 7.5 13.5 9 11
        C10.5 13.5 13.5 13.5 15 11
        C16.5 13.5 20 13 20 10"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinecap="round"
      />

      <path
        d="M6 13V20H18V13M10 20V15H14V20"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinejoin="round"
      />
    </svg>
  );
}

/* ==========================================
   ICON TRANSAKSI
========================================== */

function ChartIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path
        d="M3 21H21M5 18V12H9V18M11 18V5H15V18M17 18V9H21V18"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinecap="round"
      />
    </svg>
  );
}

/* ==========================================
   BACKGROUND ART
   SEMUA DEKORASI DALAM SATU SVG
========================================== */

function BackgroundArt() {
  return (
    <svg
      className="kk-background"
      viewBox="0 0 1750 870"
      preserveAspectRatio="xMidYMid slice"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <defs>
        <linearGradient
          id="kk-cream-glow"
          x1="0"
          y1="0"
          x2="1"
          y2="1"
        >
          <stop offset="0%" stopColor="#FFFFFF" />
          <stop offset="100%" stopColor="#FFFDF0" />
        </linearGradient>

        <linearGradient
          id="kk-yellow-wave"
          x1="0"
          y1="0"
          x2="1"
          y2="1"
        >
          <stop offset="0%" stopColor="#FFF1A7" />
          <stop offset="100%" stopColor="#FFE566" />
        </linearGradient>
      </defs>

      {/* BACKGROUND UTAMA */}

      <rect
        width="1750"
        height="870"
        fill="url(#kk-cream-glow)"
      />

      {/* LINGKARAN KREM KIRI */}

      <circle
        cx="-135"
        cy="265"
        r="225"
        fill="#FFF2C4"
        opacity="0.30"
      />

      {/* LINGKARAN KUNING KANAN ATAS */}

      <circle
        cx="1740"
        cy="-45"
        r="270"
        fill="#FFE566"
      />

      <circle
        cx="1760"
        cy="-55"
        r="195"
        fill="#FFF0A3"
        opacity="0.65"
      />

      {/* TITIK DEKORASI */}

      <circle cx="1480" cy="70" r="12" fill="#CD1C18" />
      <circle cx="1685" cy="223" r="11" fill="#FFE566" />
      <circle cx="46" cy="572" r="11" fill="#FFE566" />
      <circle cx="235" cy="740" r="11" fill="#FFE566" />

      {/* ==================================
          ILUSTRASI KANTIN KANAN BAWAH
      ================================== */}

      <g
        transform="translate(1590 485)"
        fill="none"
        stroke="#F4DF91"
        strokeWidth="5"
        strokeLinecap="round"
        strokeLinejoin="round"
        opacity="0.62"
      >
        {/* ATAP KIOS */}

        <path d="M0 75L92 12L190 75" />
        <path d="M0 75H190" />
        <path d="M18 75L45 43" />
        <path d="M62 75L92 12" />
        <path d="M113 75L145 43" />
        <path d="M157 75L173 56" />

        {/* TIANG KIOS */}

        <path d="M20 75V248" />
        <path d="M172 75V248" />
        <path d="M4 248H188" />

        {/* GARPU */}

        <path d="M95 125V161" />
        <path d="M109 125V161" />
        <path d="M123 125V161" />
        <path d="M95 161Q109 175 123 161" />
        <path d="M109 171V234" />

        {/* SENDOK */}

        <path
          d="
            M151 126
            C134 126 133 159 151 167
            C168 159 168 126 151 126
          "
        />

        <path d="M151 167V234" />

        {/* MANGKUK MAKANAN */}

        <path d="M-90 232Q-44 193 6 232" />
        <path d="M-100 235H15" />
        <path d="M-87 245Q-75 284-42 284Q-10 284 3 245" />

        {/* TOPPING MAKANAN */}

        <path d="M-81 225Q-72 210-61 221" />
        <path d="M-57 220Q-46 203-34 218" />
        <path d="M-31 220Q-18 210-8 225" />
      </g>

      {/* ==================================
          GELOMBANG KIRI BAWAH
      ================================== */}

      <path
        d="
          M0 645
          C105 686 180 782 315 811
          C420 833 540 839 660 870
          H0
          Z
        "
        fill="url(#kk-yellow-wave)"
      />

      {/* LAPISAN MAROON KIRI */}

      <path
        d="
          M245 825
          C310 800 356 775 402 765
          C450 755 495 792 547 829
          C583 855 621 866 660 870
          H300
          Z
        "
        fill="#720012"
      />

      {/* GARIS TIPIS KIRI */}

      <path
        d="
          M0 610
          C78 623 108 740 231 791
          C350 835 455 806 562 811
          C630 816 693 845 750 870
        "
        fill="none"
        stroke="#CD1C18"
        strokeWidth="1.5"
      />

      {/* ==================================
          GELOMBANG KANAN BAWAH
      ================================== */}

      {/* KUNING */}

      <path
        d="
          M1190 870
          C1290 822 1365 796 1445 790
          C1520 785 1580 799 1635 793
          C1690 786 1720 761 1750 725
          L1750 870
          Z
        "
        fill="#FFE566"
      />

      {/* MERAH */}

      <path
        d="
          M1585 870
          C1635 854 1675 827 1705 795
          C1725 774 1738 746 1750 725
          L1750 870
          Z
        "
        fill="#CD1C18"
      />

      {/* GARIS LENGKUNG KANAN */}

      <path
        d="
          M1265 870
          C1370 850 1450 835 1525 820
          C1620 800 1695 775 1750 725
        "
        fill="none"
        stroke="#EFA29A"
        strokeWidth="1.5"
        strokeLinecap="round"
      />
    </svg>
  );
}

/* ==========================================
   LOGIN INTERNAL
   TERHUBUNG KE LARAVEL + MYSQL
========================================== */

export default function LoginInternal() {
  const navigate = useNavigate();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [remember, setRemember] = useState(false);
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);

  /* ==========================================
     PROSES LOGIN
  ========================================== */

  const handleLogin = async (e) => {
    e.preventDefault();

    setMessage("");

    if (!email.trim() || !password.trim()) {
      setMessage("Email dan password wajib diisi.");
      return;
    }

    setLoading(true);

    try {
      const result = await loginInternal(
        email.trim(),
        password
      );

      const user = result?.user;

      if (!user) {
        throw new Error(
          "Data pengguna tidak ditemukan dalam respons server."
        );
      }

      if (user.role === "admin") {
        navigate("/admin/dashboard", {
          replace: true,
        });
      } else if (user.role === "tenant") {
        navigate("/tenant/dashboard", {
          replace: true,
        });
      } else {
        setMessage(
          "Role akun tidak dikenali. Hubungi administrator."
        );
      }
    } catch (error) {
      setMessage(
        error.message ||
          "Login gagal. Silakan coba kembali."
      );
    } finally {
      setLoading(false);
    }
  };

  /* ==========================================
     TAMPILAN LOGIN
  ========================================== */

  return (
    <div className="login-internal-page">
      <BackgroundArt />

      {/* HEADER */}

      <header className="kk-header">
        <Logo />
      </header>

      {/* MAIN */}

      <main className="kk-main">
        {/* BAGIAN KIRI */}

        <section className="kk-hero">
          <p className="kk-eyebrow">
            SISTEM TRANSAKSI KANTIN
          </p>

          <h1 className="kk-title">
            Kelola kantin
            <br />
            lebih mudah
            <br />
            <span>dan terintegrasi.</span>
          </h1>

          <p className="kk-description">
            Satu sistem untuk membantu tenant dan kasir utama
            mengelola menu, pesanan, transaksi, serta laporan
            penjualan dengan lebih praktis.
          </p>

          <div className="kk-features">
            <div className="kk-feature">
              <div className="kk-feature-icon">
                <StoreIcon />
              </div>

              <div className="kk-feature-text">
                <h3>Manajemen Tenant</h3>
                <p>Kelola menu dan operasional kantin.</p>
              </div>
            </div>

            <div className="kk-feature-divider" />

            <div className="kk-feature">
              <div className="kk-feature-icon">
                <ChartIcon />
              </div>

              <div className="kk-feature-text">
                <h3>Transaksi Terintegrasi</h3>
                <p>Pantau transaksi online dan offline.</p>
              </div>
            </div>
          </div>
        </section>

        {/* CARD LOGIN */}

        <section className="kk-login-card">
          <Logo small />

          <h2>Selamat Datang</h2>

          <p className="kk-card-description">
            Masuk menggunakan akun Tenant atau Kasir Utama/Admin.
          </p>

          <form className="kk-form" onSubmit={handleLogin}>
            {/* EMAIL */}

            <div className="kk-form-group">
              <label htmlFor="kk-email">Email</label>

              <div className="kk-input-box">
                <span className="kk-input-icon">
                  <MailIcon />
                </span>

                <input
                  id="kk-email"
                  type="email"
                  placeholder="Masukkan alamat email"
                  value={email}
                  onChange={(e) => {
                    setEmail(e.target.value);
                    setMessage("");
                  }}
                  autoComplete="username"
                  disabled={loading}
                  required
                />
              </div>
            </div>

            {/* PASSWORD */}

            <div className="kk-form-group">
              <label htmlFor="kk-password">Password</label>

              <div className="kk-input-box">
                <span className="kk-input-icon">
                  <LockIcon />
                </span>

                <input
                  id="kk-password"
                  type={showPassword ? "text" : "password"}
                  placeholder="Masukkan password"
                  value={password}
                  onChange={(e) => {
                    setPassword(e.target.value);
                    setMessage("");
                  }}
                  autoComplete="current-password"
                  disabled={loading}
                  required
                />

                <button
                  type="button"
                  className="kk-show-password"
                  onClick={() => setShowPassword(!showPassword)}
                  disabled={loading}
                >
                  {showPassword ? "Tutup" : "Lihat"}
                </button>
              </div>
            </div>

            {/* INGAT SAYA */}

            <label className="kk-remember">
              <input
                type="checkbox"
                checked={remember}
                onChange={(e) => setRemember(e.target.checked)}
                disabled={loading}
              />

              <span>Ingat saya</span>
            </label>

            {/* TOMBOL MASUK */}

            <button
              type="submit"
              className="kk-login-button"
              disabled={loading}
            >
              <span>
                {loading ? "Sedang masuk..." : "Masuk"}
              </span>

              <span className="kk-button-arrow">
                {loading ? "..." : "→"}
              </span>
            </button>

            {/* PESAN ERROR */}

            {message && (
              <p
                className="kk-message"
                role="alert"
                aria-live="polite"
              >
                {message}
              </p>
            )}
          </form>

          <div className="kk-card-footer">
            Sistem Transaksi Terintegrasi untuk Kantin Multi-Tenant
          </div>
        </section>
      </main>

      {/* FOOTER */}

      <footer className="kk-footer">
        © 2026 KantinKita
      </footer>
    </div>
  );
}
