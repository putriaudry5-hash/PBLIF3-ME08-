import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import "../../styles/DashboardPelanggan.css";

const API_URL =
  import.meta.env.VITE_API_URL || "http://127.0.0.1:8000";

const GAMBAR_DEFAULT =
  "https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=700";

function DashboardPelanggan() {
  const navigate = useNavigate();

  // Mengambil data pengguna yang disimpan saat login Google
  const [user] = useState(() => {
    try {
      return JSON.parse(
        localStorage.getItem("kantinkita_user") || "null"
      );
    } catch {
      return null;
    }
  });

  const namaProfil =
    user?.name ||
    user?.nama ||
    user?.full_name ||
    user?.username ||
    "Pelanggan";

  const fotoProfil =
    user?.picture ||
    user?.avatar ||
    user?.photo_url ||
    user?.foto ||
    user?.profile_picture ||
    user?.image ||
    "";

  const [tenants, setTenants] = useState([]);
  const [jumlahKeranjang, setJumlahKeranjang] = useState(0);
  const [loadingTenant, setLoadingTenant] = useState(true);
  const [errorTenant, setErrorTenant] = useState("");

  // Mengambil tenant langsung dari database melalui Laravel API
  useEffect(() => {
    const controller = new AbortController();

    async function ambilTenant() {
      try {
        setLoadingTenant(true);
        setErrorTenant("");

        const response = await fetch(`${API_URL}/api/tenants`, {
          method: "GET",
          headers: {
            Accept: "application/json",
          },
          signal: controller.signal,
        });

        if (!response.ok) {
          throw new Error(
            `Gagal mengambil data tenant (${response.status}).`
          );
        }

        const hasil = await response.json();

        // Mendukung respons berbentuk array atau object pembungkus
        const dataTenant = Array.isArray(hasil)
          ? hasil
          : Array.isArray(hasil.data)
          ? hasil.data
          : Array.isArray(hasil.tenants)
          ? hasil.tenants
          : Array.isArray(hasil.data?.tenants)
          ? hasil.data.tenants
          : [];

        if (!Array.isArray(dataTenant)) {
          throw new Error("Format data tenant dari API tidak sesuai.");
        }

        // Menggunakan data tenant dari database
        setTenants(
          dataTenant.map((tenant) => ({
            id: tenant.id,
            nama: tenant.nama ?? "Tenant",
            kategori:
              tenant.kategori ??
              tenant.keterangan ??
              tenant.jenis ??
              "Tenant Kantin",
            jenis: tenant.jenis ?? "Tidak ditentukan",
            gambar: tenant.foto
              ? tenant.foto.startsWith("http")
                ? tenant.foto
                : `${API_URL}/storage/${tenant.foto.replace(/^\/+/, "")}`
              : GAMBAR_DEFAULT,
            status: tenant.status ?? "Tidak diketahui",
          }))
        );
      } catch (error) {
        if (error.name !== "AbortError") {
          console.error("Gagal memuat tenant:", error);
          setErrorTenant(
            "Data tenant gagal dimuat. Periksa koneksi ke server Laravel."
          );
        }
      } finally {
        if (!controller.signal.aborted) {
          setLoadingTenant(false);
        }
      }
    }

    ambilTenant();

    return () => controller.abort();
  }, []);

  // Mengambil jumlah barang di keranjang pelanggan
  useEffect(() => {
    const controller = new AbortController();

    async function ambilKeranjang() {
      try {
        const token = localStorage.getItem("kantinkita_token");

        const response = await fetch(`${API_URL}/api/cart`, {
          method: "GET",
          credentials: "include",
          headers: {
            Accept: "application/json",
            ...(token
              ? { Authorization: `Bearer ${token}` }
              : {}),
          },
          signal: controller.signal,
        });

        if (!response.ok) {
          if (response.status === 401) {
            setJumlahKeranjang(0);
            return;
          }

          throw new Error("Gagal mengambil data keranjang.");
        }

        const hasil = await response.json();

        const items =
          hasil.items ??
          hasil.cart?.items ??
          hasil.data?.items ??
          hasil.data?.cart?.items ??
          [];

        const total = Array.isArray(items)
          ? items.reduce(
              (jumlah, item) =>
                jumlah + Number(item.quantity ?? item.qty ?? 1),
              0
            )
          : 0;

        setJumlahKeranjang(total);
      } catch (error) {
        if (error.name !== "AbortError") {
          console.error("Gagal memuat keranjang:", error);
        }
      }
    }

    ambilKeranjang();

    return () => controller.abort();
  }, []);

  return (
    <div className="beranda-pelanggan">
      {/* NAVBAR */}
      <header className="beranda-navbar">
        <a
          href="/beranda"
          className="beranda-brand"
          onClick={(e) => {
            e.preventDefault();
            navigate("/beranda");
          }}
        >
          <span className="beranda-logo">K</span>
          <span>
            Kantin<span>Kita</span>
          </span>
        </a>

        <nav className="beranda-nav-links">
          <a
            href="/beranda"
            className="nav-active"
            onClick={(e) => {
              e.preventDefault();
              navigate("/beranda");
            }}
          >
            Dashboard
          </a>

          <a
            href="/pesanan-saya"
            onClick={(e) => {
              e.preventDefault();
              navigate("/pesanan-saya");
            }}
          >
            Pesanan Saya
          </a>

          <button
            type="button"
            className="nav-cart-button"
            onClick={() => navigate("/keranjang")}
            aria-label={`Keranjang, ${jumlahKeranjang} barang`}
          >
            <span className="nav-cart-icon">🛒</span>
            <span>Keranjang</span>

            {jumlahKeranjang > 0 && (
              <span className="cart-badge">
                {jumlahKeranjang > 99 ? "99+" : jumlahKeranjang}
              </span>
            )}
          </button>

          {/* PROFIL PELANGGAN */}
          <a
            href="/profil"
            className="nav-profile"
            onClick={(e) => {
              e.preventDefault();
              navigate("/profil");
            }}
          >
            <span className="profile-avatar">
              {fotoProfil ? (
                <img
                  src={fotoProfil}
                  alt="Foto profil"
                  referrerPolicy="no-referrer"
                  onError={(e) => {
                    e.currentTarget.style.display = "none";
                    const fallback = e.currentTarget.nextElementSibling;
                    if (fallback) {
                      fallback.style.display = "flex";
                    }
                  }}
                />
              ) : null}

              <span style={{ display: fotoProfil ? "none" : "flex" }}>
                {namaProfil.trim().charAt(0).toUpperCase() || "P"}
              </span>
            </span>

            <span className="profile-name">{namaProfil}</span>
          </a>
        </nav>
      </header>

      {/* KONTEN DASHBOARD */}
      <main className="beranda-content">
        <section className="beranda-hero">
          <div className="hero-text">
            <span className="hero-label">
              DASHBOARD PELANGGAN
            </span>

            <h1>
              Mau makan apa
              <br />
              hari ini?
            </h1>

            <p>
              Pilih tenant yang kamu inginkan, lihat menu
              yang tersedia, lalu lakukan pemesanan sebelum
              waktu istirahatmu berakhir.
            </p>

            <a href="#daftar-tenant" className="hero-button">
              Lihat Tenant
            </a>
          </div>

          <div className="hero-image">
            <img
              src="https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1000"
              alt="Suasana dapur"
            />

            <div className="hero-image-caption">
              <span>🍽️</span>
              Banyak pilihan, lebih praktis!
            </div>
          </div>
        </section>

        {/* DAFTAR TENANT DARI API */}
        <section
          className="tenant-section"
          id="daftar-tenant"
        >
          <div className="tenant-section-heading">
            <div>
              <span className="section-eyebrow">
                PILIH FAVORITMU
              </span>

              <h2>Daftar Tenant</h2>

              <p>
                Pilih tenant untuk melihat menu yang tersedia.
              </p>
            </div>

            <span className="tenant-total">
              {tenants.length} Tenant
            </span>
          </div>

          {loadingTenant ? (
            <div className="tenant-empty">
              <p>Memuat data tenant...</p>
            </div>
          ) : errorTenant ? (
            <div className="tenant-empty" role="alert">
              <p>{errorTenant}</p>
              <button
                type="button"
                className="tenant-menu-button"
                onClick={() => window.location.reload()}
              >
                Coba Lagi
              </button>
            </div>
          ) : tenants.length === 0 ? (
            <div className="tenant-empty">
              <p>Belum ada tenant yang tersedia.</p>
              <span>
                Tenant akan muncul di sini setelah datanya
                ditambahkan ke database.
              </span>
            </div>
          ) : (
            <div className="tenant-grid">
              {tenants.map((tenant) => (
                <article
                  className="tenant-card"
                  key={tenant.id}
                >
                  <div className="tenant-image-wrap">
                    <img
                      className="tenant-image"
                      src={tenant.gambar}
                      alt={tenant.nama}
                      loading="lazy"
                    />

                    <span className="tenant-status">
                      <span className="status-dot"></span>
                      {tenant.status}
                    </span>
                  </div>

                  <div className="tenant-card-body">
                    <h3>{tenant.nama}</h3>

                    <p className="tenant-category">
                      {tenant.kategori}
                    </p>

                    <div className="tenant-info">
                      <span>🍴 {tenant.jenis}</span>
                    </div>

                    <button
                      type="button"
                      className="tenant-menu-button"
                      onClick={() =>
                        navigate(`/tenant/${tenant.id}/menu`)
                      }
                    >
                      Lihat Menu <span>→</span>
                    </button>
                  </div>
                </article>
              ))}
            </div>
          )}
        </section>

        {/* FOOTER */}
        <footer className="beranda-footer">
          © 2026 KantinKita · Pesan makanan kampus lebih praktis.
        </footer>
      </main>
    </div>
  );
}

export default DashboardPelanggan;