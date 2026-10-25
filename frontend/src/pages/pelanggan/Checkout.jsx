import { useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import "../../styles/Checkout.css";

function formatRupiah(angka) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(Number(angka) || 0);
}

const metodePembayaran = [
  {
    id: "qris",
    nama: "QRIS",
    deskripsi: "Simulasi pembayaran QRIS",
    ikon: "▦",
  },
  {
    id: "dana",
    nama: "DANA",
    deskripsi: "Simulasi e-wallet DANA",
    ikon: "D",
  },
  {
    id: "ovo",
    nama: "OVO",
    deskripsi: "Simulasi e-wallet OVO",
    ikon: "O",
  },
  {
    id: "gopay",
    nama: "GoPay",
    deskripsi: "Simulasi e-wallet GoPay",
    ikon: "G",
  },
];

export default function Checkout() {
  const navigate = useNavigate();
  const location = useLocation();

  const items = Array.isArray(location.state?.itemDipilih)
    ? location.state.itemDipilih
    : [];

  const [metode, setMetode] = useState("qris");

  const totalItem = items.reduce(
    (total, item) =>
      total + Math.max(0, Number(item.quantity) || 0),
    0
  );

  const totalHarga = items.reduce(
    (total, item) =>
      total +
      Math.max(
        0,
        Number(item.harga ?? item.selected_price) || 0
      ) *
        Math.max(0, Number(item.quantity) || 0),
    0
  );

  const namaMetode =
    metodePembayaran.find((item) => item.id === metode)?.nama ||
    "QRIS";

  // Klik tombol pembayaran akan langsung membuka halaman hasil.
  function simulasiPembayaran() {
    if (!items.length || totalHarga <= 0) {
      return;
    }

    const nomor = "DEMO-" + Date.now().toString().slice(-8);

    navigate("/hasil-pembayaran", {
      state: {
        items,
        nomorSimulasi: nomor,
        metode: namaMetode,
        totalItem,
        totalHarga,
      },
    });
  }

  if (!items.length) {
    return (
      <div className="checkout-page">
        <header className="checkout-navbar">
          <button
            type="button"
            onClick={() => navigate("/beranda")}
          >
            KantinKita
          </button>

          <span>Checkout</span>
        </header>

        <main className="checkout-container">
          <section className="checkout-empty">
            <div className="checkout-empty-icon">🛒</div>

            <h1>Belum ada menu untuk checkout</h1>

            <p>
              Silakan pilih menu dari keranjang terlebih dahulu.
            </p>

            <button
              type="button"
              className="checkout-button"
              onClick={() => navigate("/keranjang")}
            >
              Kembali ke Keranjang
            </button>
          </section>
        </main>
      </div>
    );
  }

  return (
    <div className="checkout-page">
      <header className="checkout-navbar">
        <button
          type="button"
          className="checkout-brand"
          onClick={() => navigate("/beranda")}
        >
          <span className="checkout-logo">K</span>

          <span>
            Kantin
            <span className="checkout-brand-highlight">
              Kita
            </span>
          </span>
        </button>

        <div className="checkout-nav-title">
          Checkout dan Pembayaran
        </div>

        <button
          type="button"
          className="checkout-back"
          onClick={() => navigate("/keranjang")}
        >
          ← Keranjang
        </button>
      </header>

      <main className="checkout-container">
        <div className="checkout-breadcrumb">
          <button
            type="button"
            onClick={() => navigate("/beranda")}
          >
            Beranda
          </button>

          <span>/</span>

          <button
            type="button"
            onClick={() => navigate("/keranjang")}
          >
            Keranjang
          </button>

          <span>/</span>

          <span>Checkout</span>
        </div>

        <section className="checkout-heading">
          <span className="checkout-eyebrow">
            LANGKAH TERAKHIR
          </span>

          <h1>Checkout Pesanan</h1>

          <p>
            Periksa kembali pesanan dan pilih metode pembayaran.
          </p>
        </section>

        <div className="checkout-demo-notice">
          <span>🧪</span>

          <div>
            <strong>
              MODE SIMULASI — BELUM TERHUBUNG KE PAYKITA
            </strong>

            <p>
              Pembayaran ini hanya untuk demonstrasi.
              Tidak ada uang yang ditransfer dan tidak ada
              transaksi sungguhan yang diproses.
            </p>
          </div>
        </div>

        <div className="checkout-layout">
          <section className="checkout-card">
            <div className="checkout-card-heading">
              <span className="checkout-step">1</span>

              <div>
                <h2>Ringkasan Menu</h2>
                <p>{totalItem} item dipilih</p>
              </div>
            </div>

            <div className="checkout-items">
              {items.map((item, index) => {
                const harga = Math.max(
                  0,
                  Number(item.harga ?? item.selected_price) || 0
                );

                const jumlah = Math.max(
                  0,
                  Number(item.quantity) || 0
                );

                const namaMenu =
                  item.nama_menu || item.nama || "Menu";

                const foto = item.foto || item.gambar;

                return (
                  <article
                    className="checkout-item"
                    key={`${
                      item.id ?? item.menu_id ?? "menu"
                    }-${index}`}
                  >
                    <div className="checkout-item-image">
                      {foto ? (
                        <img
                          src={foto}
                          alt={namaMenu}
                          onError={(event) => {
                            event.currentTarget.style.display =
                              "none";
                          }}
                        />
                      ) : (
                        <span>🍱</span>
                      )}
                    </div>

                    <div className="checkout-item-info">
                      <strong>{namaMenu}</strong>

                      <span>
                        {item.nama_tenant ||
                          "Tenant KantinKita"}
                      </span>

                      <span>
                        {formatRupiah(harga)} × {jumlah}
                      </span>
                    </div>

                    <strong className="checkout-item-subtotal">
                      {formatRupiah(harga * jumlah)}
                    </strong>
                  </article>
                );
              })}
            </div>

            <button
              type="button"
              className="checkout-text-button"
              onClick={() => navigate("/keranjang")}
            >
              ← Ubah pilihan menu
            </button>

            <div className="checkout-divider" />

            <div className="checkout-card-heading">
              <span className="checkout-step">2</span>

              <div>
                <h2>Metode Pembayaran</h2>

                <p>
                  Pilih metode untuk simulasi pembayaran.
                </p>
              </div>
            </div>

            <div className="checkout-methods">
              {metodePembayaran.map((item) => (
                <button
                  type="button"
                  key={item.id}
                  className={`checkout-method ${
                    metode === item.id ? "selected" : ""
                  }`}
                  onClick={() => setMetode(item.id)}
                  aria-pressed={metode === item.id}
                >
                  <span
                    className={`checkout-method-icon ${item.id}`}
                  >
                    {item.ikon}
                  </span>

                  <span className="checkout-method-info">
                    <strong>{item.nama}</strong>
                    <small>{item.deskripsi}</small>
                  </span>

                  <span className="checkout-radio">
                    {metode === item.id ? "✓" : ""}
                  </span>
                </button>
              ))}
            </div>

            <p className="checkout-method-note">
              Metode pembayaran di atas hanya digunakan untuk
              simulasi antarmuka dan belum memproses transaksi.
            </p>
          </section>

          <aside className="checkout-summary">
            <h2>Ringkasan Pembayaran</h2>

            <div className="checkout-summary-row">
              <span>Total jenis menu</span>
              <span>{items.length} jenis</span>
            </div>

            <div className="checkout-summary-row">
              <span>Total item</span>
              <span>{totalItem} item</span>
            </div>

            <div className="checkout-summary-row">
              <span>Subtotal</span>
              <span>{formatRupiah(totalHarga)}</span>
            </div>

            <div className="checkout-summary-row">
              <span>Biaya layanan</span>
              <span>{formatRupiah(0)}</span>
            </div>

            <div className="checkout-divider" />

            <div className="checkout-summary-row">
              <span>Metode pembayaran</span>
              <strong>{namaMetode}</strong>
            </div>

            <div className="checkout-total">
              <span>Total Pembayaran</span>
              <strong>{formatRupiah(totalHarga)}</strong>
            </div>

            <button
              type="button"
              className="checkout-button checkout-pay-button"
              onClick={simulasiPembayaran}
              disabled={!items.length || totalHarga <= 0}
            >
              Simulasikan Pembayaran
              <span>→</span>
            </button>

            <p className="checkout-secure-note">
              🔒 Ini hanya simulasi. Pembayaran sungguhan belum
              tersedia.
            </p>
          </aside>
        </div>
      </main>
    </div>
  );
}