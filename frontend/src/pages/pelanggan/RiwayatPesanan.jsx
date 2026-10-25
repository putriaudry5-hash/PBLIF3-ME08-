import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import "../../styles/RiwayatPesanan.css";

const KEY_PESANAN = "kantinkita_pesanan";

function bacaPesanan() {
  try {
    const data = JSON.parse(
      localStorage.getItem(KEY_PESANAN) || "[]"
    );

    return Array.isArray(data) ? data : [];
  } catch {
    return [];
  }
}

function statusPesanan(item) {
  const status = String(item.status || "").toLowerCase();

  if (status.includes("batal") || status.includes("cancel")) {
    return "Dibatalkan";
  }

  const index = Number(item.statusIndex);

  if (status.includes("selesai") || index >= 3) {
    return "Selesai";
  }

  return "";
}

function formatRupiah(angka) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0,
  }).format(Number(angka) || 0);
}

function formatTanggal(tanggal) {
  if (!tanggal) return "-";

  const date = new Date(tanggal);

  if (Number.isNaN(date.getTime())) return "-";

  return date.toLocaleString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

function namaMenu(menu) {
  return (
    menu.nama ||
    menu.nama_menu ||
    menu.name ||
    menu.nama_produk ||
    "Menu"
  );
}

function jumlahMenu(menu) {
  return Number(menu.quantity ?? menu.jumlah ?? menu.qty ?? 1);
}

export default function RiwayatPesanan() {
  const navigate = useNavigate();
  const [daftarPesanan, setDaftarPesanan] = useState([]);

  useEffect(() => {
    const muatData = () => {
      const riwayat = bacaPesanan()
        .filter((item) => statusPesanan(item) !== "")
        .sort(
          (a, b) =>
            new Date(b.tanggal || 0).getTime() -
            new Date(a.tanggal || 0).getTime()
        );

      setDaftarPesanan(riwayat);
    };

    muatData();

    window.addEventListener("focus", muatData);
    window.addEventListener("storage", muatData);
    window.addEventListener("pesanan-diperbarui", muatData);

    return () => {
      window.removeEventListener("focus", muatData);
      window.removeEventListener("storage", muatData);
      window.removeEventListener("pesanan-diperbarui", muatData);
    };
  }, []);

  return (
    <main className="riwayat-page">
      <div className="riwayat-container">
        <button
          type="button"
          className="riwayat-kembali"
          onClick={() => navigate("/profil")}
        >
          <span aria-hidden="true">←</span>
          Kembali ke Profil
        </button>

        <header className="riwayat-header">
          <span className="riwayat-eyebrow">
            AKTIVITAS KAMU
          </span>

          <h1>Riwayat Pesanan</h1>

          <p>
            Daftar pesanan yang sudah selesai atau dibatalkan.
          </p>
        </header>

        <section className="riwayat-list-section">
          <div className="riwayat-list-heading">
            <div>
              <h2>Daftar Transaksi</h2>
              <p>Riwayat transaksi KantinKita.</p>
            </div>

            <span className="riwayat-count">
              {daftarPesanan.length} pesanan
            </span>
          </div>

          {daftarPesanan.length === 0 ? (
            <div className="riwayat-kosong">
              <div className="riwayat-empty-icon">📋</div>

              <h3>Belum Ada Riwayat Pesanan</h3>

              <p>
                Pesanan yang selesai atau dibatalkan akan
                ditampilkan di sini.
              </p>

              <button
                type="button"
                className="riwayat-cari-button"
                onClick={() => navigate("/beranda")}
              >
                Cari Makanan →
              </button>
            </div>
          ) : (
            <div className="riwayat-table-wrapper">
              <table className="riwayat-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Nomor Pesanan</th>
                    <th>Tanggal</th>
                    <th>Nama Kantin</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                  </tr>
                </thead>

                <tbody>
                  {daftarPesanan.map((item, index) => {
                    const items = Array.isArray(item.items)
                      ? item.items
                      : [];

                    const jumlah =
                      item.totalItem ??
                      items.reduce(
                        (total, menu) =>
                          total + jumlahMenu(menu),
                        0
                      );

                    const nomorPesanan =
                      item.nomorSimulasi || item.id || "-";

                    const tenant =
                      item.namaTenant ||
                      item.tenant?.nama ||
                      item.tenant?.name ||
                      item.tenantName ||
                      "Tenant KantinKita";

                    const status = statusPesanan(item);

                    return (
                      <tr
                        key={
                          item.id ||
                          item.nomorSimulasi ||
                          `${item.tanggal || "pesanan"}-${index}`
                        }
                      >
                        <td className="riwayat-nomor">
                          {index + 1}
                        </td>

                        <td className="riwayat-id">
                          #{nomorPesanan}
                        </td>

                        <td className="riwayat-tanggal">
                          {formatTanggal(item.tanggal)}
                        </td>

                        <td className="riwayat-tenant">
                          {tenant}
                        </td>

                        <td>
                          <div className="riwayat-menu-list">
                            {items.length > 0 ? (
                              items.map((menu, menuIndex) => (
                                <div
                                  key={menuIndex}
                                  className="riwayat-menu-item"
                                >
                                  <span>{namaMenu(menu)}</span>
                                  <small>
                                    {jumlahMenu(menu)} ×{" "}
                                    {formatRupiah(
                                      menu.harga ??
                                      menu.price ??
                                      menu.harga_satuan ??
                                      0
                                    )}
                                  </small>
                                </div>
                              ))
                            ) : (
                              <span className="riwayat-tidak-ada">
                                Detail tidak tersedia
                              </span>
                            )}
                          </div>
                        </td>

                        <td>{jumlah} item</td>

                        <td className="riwayat-total">
                          {formatRupiah(item.totalHarga)}
                        </td>

                        <td>
                          <span
                            className={`riwayat-status ${
                              status === "Selesai"
                                ? "status-selesai"
                                : "status-dibatalkan"
                            }`}
                          >
                            {status}
                          </span>
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
          )}
        </section>

        <footer className="riwayat-footer">
          KantinKita · Riwayat transaksi
        </footer>
      </div>
    </main>
  );
}