import { useLocation, useNavigate } from "react-router-dom";
import "../../styles/DetailPesanan.css";

function formatRupiah(angka) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(angka || 0);
}

export default function DetailPesanan() {
  const navigate = useNavigate();
  const location = useLocation();

  const data = location.state || {};
  const items = Array.isArray(data.items) ? data.items : [];

  const nomorPesanan = data.nomorSimulasi || "-";
  const metode = data.metode || "-";

  const totalHarga = items.reduce((total, item) => {
    const harga = Math.max(
      0,
      Number(item.harga ?? item.selected_price) || 0
    );
    const jumlah = Math.max(0, Number(item.quantity) || 0);

    return total + harga * jumlah;
  }, 0);

  const totalItem = items.reduce(
    (total, item) =>
      total + Math.max(0, Number(item.quantity) || 0),
    0
  );

  const kelompokTenant = Object.values(
    items.reduce((kelompok, item, index) => {
      const namaTenant = item.nama_tenant || "Tenant KantinKita";

      if (!kelompok[namaTenant]) {
        kelompok[namaTenant] = {
          nama: namaTenant,
          items: [],
          subtotal: 0,
        };
      }

      const harga = Math.max(
        0,
        Number(item.harga ?? item.selected_price) || 0
      );
      const jumlah = Math.max(0, Number(item.quantity) || 0);

      kelompok[namaTenant].items.push({
        ...item,
        key: `${item.id ?? item.menu_id ?? "menu"}-${index}`,
        harga,
        jumlah,
        subtotal: harga * jumlah,
      });

      kelompok[namaTenant].subtotal += harga * jumlah;

      return kelompok;
    }, {})
  );

  if (!items.length) {
    return (
      <div className="detail-pesanan-page">
        <header className="detail-pesanan-navbar">
          <button
            className="detail-pesanan-brand"
            onClick={() => navigate("/beranda")}
          >
            <span className="detail-pesanan-logo">K</span>
            <span>
              Kantin<span>Kita</span>
            </span>
          </button>
        </header>

        <main className="detail-pesanan-container">
          <section className="detail-pesanan-empty">
            <div className="detail-pesanan-empty-icon">🧾</div>
            <h1>Detail pesanan tidak tersedia</h1>
            <p>
              Data pesanan tidak ditemukan. Silakan lakukan checkout
              dan simulasi pembayaran kembali.
            </p>
            <button
              className="detail-pesanan-button"
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
    <div className="detail-pesanan-page">
      <header className="detail-pesanan-navbar">
        <button
          className="detail-pesanan-brand"
          onClick={() => navigate("/beranda")}
        >
          <span className="detail-pesanan-logo">K</span>
          <span>
            Kantin<span>Kita</span>
          </span>
        </button>

        <button
          className="detail-pesanan-back"
          onClick={() => navigate("/beranda")}
        >
          ← Beranda
        </button>
      </header>

      <main className="detail-pesanan-container">
        <div className="detail-pesanan-breadcrumb">
          <button onClick={() => navigate("/beranda")}>Beranda</button>
          <span>/</span>
          <span>Detail Pesanan</span>
        </div>

        <section className="detail-pesanan-heading">
          <span className="detail-pesanan-eyebrow">
            INFORMASI PESANAN
          </span>
          <h1>Detail Pesanan</h1>
          <p>
            Lihat makanan yang kamu pesan dari setiap tenant KantinKita.
          </p>
        </section>

        <section className="detail-pesanan-success">
          <div className="detail-pesanan-success-icon">✓</div>
          <div>
            <h2>Simulasi Pembayaran Berhasil!</h2>
            <p>
              Status ini hanya untuk demonstrasi, bukan bukti pembayaran
              sungguhan.
            </p>
          </div>
        </section>

        <section className="detail-pesanan-card">
          <h2>Informasi Pembayaran</h2>

          <div className="detail-pesanan-info-grid">
            <div>
              <span>Nomor simulasi</span>
              <strong>{nomorPesanan}</strong>
            </div>
            <div>
              <span>Metode pembayaran</span>
              <strong>{metode}</strong>
            </div>
            <div>
              <span>Status</span>
              <strong className="detail-pesanan-status">
                SIMULASI BERHASIL
              </strong>
            </div>
          </div>
        </section>

        <section className="detail-pesanan-card">
          <div className="detail-pesanan-section-heading">
            <h2>Pesanan dari Tenant</h2>
            <span>{kelompokTenant.length} tenant</span>
          </div>

          {kelompokTenant.map((tenant) => (
            <div className="detail-pesanan-tenant" key={tenant.nama}>
              <div className="detail-pesanan-tenant-heading">
                <span className="detail-pesanan-store-icon">⌂</span>
                <div>
                  <span>Nama tenant</span>
                  <h3>{tenant.nama}</h3>
                </div>
              </div>

              {tenant.items.map((item) => (
                <article
                  className="detail-pesanan-item"
                  key={item.key}
                >
                  <div className="detail-pesanan-image">
                    {item.foto ? (
                      <img
                        src={item.foto}
                        alt={item.nama_menu || item.nama || "Menu"}
                        onError={(event) => {
                          event.currentTarget.style.display = "none";
                        }}
                      />
                    ) : (
                      <span>🍱</span>
                    )}
                  </div>

                  <div className="detail-pesanan-item-info">
                    <strong>
                      {item.nama_menu || item.nama || "Menu"}
                    </strong>
                    <span>
                      {formatRupiah(item.harga)} × {item.jumlah}
                    </span>
                  </div>

                  <strong className="detail-pesanan-item-price">
                    {formatRupiah(item.subtotal)}
                  </strong>
                </article>
              ))}

              <div className="detail-pesanan-subtotal">
                <span>Subtotal {tenant.nama}</span>
                <strong>{formatRupiah(tenant.subtotal)}</strong>
              </div>
            </div>
          ))}
        </section>

        <section className="detail-pesanan-card">
          <h2>Rincian Total</h2>

          <div className="detail-pesanan-total-row">
            <span>Total jenis menu</span>
            <strong>{items.length} jenis</strong>
          </div>

          <div className="detail-pesanan-total-row">
            <span>Total item</span>
            <strong>{totalItem} item</strong>
          </div>

          <div className="detail-pesanan-total-row">
            <span>Biaya layanan</span>
            <strong>{formatRupiah(0)}</strong>
          </div>

          <div className="detail-pesanan-grand-total">
            <span>Total pembayaran</span>
            <strong>{formatRupiah(totalHarga)}</strong>
          </div>
        </section>

        <div className="detail-pesanan-actions">
          <button
            className="detail-pesanan-button detail-pesanan-button-secondary"
            onClick={() => navigate("/beranda")}
          >
            Kembali ke Beranda
          </button>

          <button
            className="detail-pesanan-button"
            onClick={() => navigate("/keranjang")}
          >
            Kembali ke Keranjang
          </button>
        </div>

        <p className="detail-pesanan-disclaimer">
          Data ini berasal dari simulasi checkout dan belum disimpan
          sebagai riwayat pesanan permanen di database.
        </p>
      </main>
    </div>
  );
}