import { useEffect, useState } from "react";
import { useNavigate, Navigate } from "react-router-dom";
import "../../styles/ProfilPelanggan.css";

const KEY_USER = "kantinkita_user";
const KEY_TOKEN = "kantinkita_token";
const KEY_PESANAN = "kantinkita_pesanan";

function bacaUser() {
  try {
    const data = localStorage.getItem(KEY_USER);
    return data ? JSON.parse(data) : null;
  } catch {
    return null;
  }
}

function bacaPesanan() {
  try {
    const data = localStorage.getItem(KEY_PESANAN);
    return data ? JSON.parse(data) : [];
  } catch {
    return [];
  }
}

function ProfilPelanggan() {
  const navigate = useNavigate();

  const [user, setUser] = useState(bacaUser);
  const [jumlahPesanan, setJumlahPesanan] = useState(
    () => bacaPesanan().length
  );
  const [konfirmasiKeluar, setKonfirmasiKeluar] = useState(false);

  useEffect(() => {
    const perbaruiData = () => {
      setUser(bacaUser());
      setJumlahPesanan(bacaPesanan().length);
    };

    window.addEventListener("focus", perbaruiData);
    window.addEventListener("storage", perbaruiData);

    return () => {
      window.removeEventListener("focus", perbaruiData);
      window.removeEventListener("storage", perbaruiData);
    };
  }, []);

  // Jika belum login, arahkan ke halaman login pelanggan.
  if (!localStorage.getItem(KEY_TOKEN) || !user) {
    return <Navigate to="/login/pelanggan" replace />;
  }

  // Mendukung beberapa kemungkinan nama kolom dari Laravel.
  const nama =
    user.name ||
    user.nama ||
    user.full_name ||
    user.username ||
    "Pelanggan";

  const email =
    user.email ||
    user.email_address ||
    "-";

  const foto =
    user.picture ||
    user.avatar ||
    user.photo_url ||
    user.foto ||
    user.profile_picture ||
    user.image ||
    "";

  const inisial = nama.trim().charAt(0).toUpperCase() || "P";

  function keluarAkun() {
    localStorage.removeItem(KEY_TOKEN);
    localStorage.removeItem(KEY_USER);

    setKonfirmasiKeluar(false);
    navigate("/login/pelanggan", { replace: true });
  }

  return (
    <main className="profil-page">
      <header className="profil-header">
        <button
          type="button"
          className="profil-back"
          onClick={() => navigate("/beranda")}
        >
          <span aria-hidden="true">←</span>
          Kembali
        </button>

        <div className="profil-heading">
          <span className="profil-eyebrow">AKUN KANTINKITA</span>
          <h1>Profil Saya</h1>
          <p>Informasi akun dan aktivitas pemesananmu.</p>
        </div>
      </header>

      <section className="profil-container">
        <article className="profil-card">
          <div className="profil-avatar">
            {foto ? (
              <img
                src={foto}
                alt={`Foto profil ${nama}`}
                referrerPolicy="no-referrer"
                onError={(e) => {
                  e.currentTarget.style.display = "none";
                  e.currentTarget.nextElementSibling.style.display = "flex";
                }}
              />
            ) : null}

            <span
              className="profil-avatar-fallback"
              style={{ display: foto ? "none" : "flex" }}
            >
              {inisial}
            </span>
          </div>

          <div className="profil-user-info">
            <span className="profil-account-label">
              PROFIL PENGGUNA
            </span>

            <h2>{nama}</h2>
            <p>{email}</p>

            <span className="profil-google-badge">
              <span className="profil-google-dot" />
              Akun Google
            </span>
          </div>
        </article>

        <section className="profil-menu-section">
          <h2>Aktivitas Saya</h2>

          <button
            type="button"
            className="profil-menu-card"
            onClick={() => navigate("/riwayat-pesanan")}
          >
            <span className="profil-menu-icon" aria-hidden="true">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="1.8"
                strokeLinecap="round"
                strokeLinejoin="round"
              >
                <path d="M6 3h9l4 4v14H6z" />
                <path d="M14 3v5h5" />
                <path d="M9 13h7M9 17h7" />
              </svg>
            </span>

            <span className="profil-menu-text">
              <strong>Riwayat Pesanan</strong>
              <span>Lihat pesanan dan statusnya.</span>
              <small>
                {jumlahPesanan} pesanan tersimpan
              </small>
            </span>

            <span className="profil-menu-arrow" aria-hidden="true">
              →
            </span>
          </button>
        </section>

        <section className="profil-logout-section">
          <button
            type="button"
            className="profil-logout-button"
            onClick={() => setKonfirmasiKeluar(true)}
          >
            <svg
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="1.8"
              strokeLinecap="round"
              strokeLinejoin="round"
              aria-hidden="true"
            >
              <path d="M10 17l5-5-5-5" />
              <path d="M15 12H3" />
              <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6" />
            </svg>

            <span>Keluar Akun</span>
          </button>
        </section>
      </section>

      {konfirmasiKeluar && (
        <div
          className="profil-modal-overlay"
          onClick={() => setKonfirmasiKeluar(false)}
        >
          <section
            className="profil-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="profil-modal-title"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="profil-modal-icon">↗</div>

            <h2 id="profil-modal-title">Keluar dari akun?</h2>

            <p>
              Kamu akan keluar dari akun KantinKita dan
              kembali ke halaman login.
            </p>

            <div className="profil-modal-actions">
              <button
                type="button"
                className="profil-cancel-button"
                onClick={() => setKonfirmasiKeluar(false)}
              >
                Batal
              </button>

              <button
                type="button"
                className="profil-confirm-logout"
                onClick={keluarAkun}
              >
                Ya, Keluar
              </button>
            </div>
          </section>
        </div>
      )}
    </main>
  );
}

export default ProfilPelanggan;