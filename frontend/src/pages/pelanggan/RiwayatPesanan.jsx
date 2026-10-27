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
  const index = Number(item.statusIndex);

  if (
    status.includes("batal") ||
    status.includes("cancel")
  ) {
    return "Dibatalkan";
  }

  if (
    status.includes("selesai") ||
    index >= 3
  ) {
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
  return Number(
    menu.quantity ?? menu.jumlah ?? menu.qty ?? 1
  );
}

function hargaMenu(menu) {
  return Number(
    menu.harga ??
    menu.harga_satuan ??
    menu.price ??
    0
  );
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
            RIWAYAT TRANSAKSI
          </span>

          <h1>Riwayat Pesanan</h1>

          <p>
            Daftar pesanan yang sudah selesai atau dibatalkan.
            Kamu bisa melihat kembali menu dan transaksi sebelumnya.
          </p>
        </header>

        <section className="riwayat-list-section">
          <div className="riwayat-list-heading">
            <div>
              <h2>Daftar Riwayat Pesanan</h2>
              <p>
                Pesanan yang sudah mencapai status akhir.
              </p>
            </div>

            <span className="riwayat-count">
              {daftarPesanan.length} pesanan
            </span>
          </div>

          {daftarPesanan.length === 0 ? (
            <div className="riwayat-kosong">
              <div className="riwayat-empty-icon">
                🧾
              </div>

              <h3>Belum Ada Riwayat Pesanan</h3>

              <p>
                Pesanan yang sudah selesai atau dibatalkan
                akan muncul di halaman ini.
              </p>

              <button
                type="button"
                className="riwayat-cari-button"
                onClick={() => navigate("/")}
              >
                Cari Menu
              </button>
            </div>
          ) : (
            <div className="riwayat-table-wrapper">
              <table className="riwayat-table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>ID Pesanan</th>
                    <th>Tanggal</th>
                    <th>Tenant</th>
                    <th>Menu Pesanan</th>
                    <th>Total</th>
                    <th>Status</th>
                  </tr>
                </thead>

                <tbody>
                  {daftarPesanan.map((pesanan, index) => {
                    const status = statusPesanan(pesanan);

                    const items = Array.isArray(pesanan.items)
                      ? pesanan.items
                      : Array.isArray(pesanan.detail)
                        ? pesanan.detail
                        : [];

                    const totalItem = items.reduce(
                      (total, menu) =>
                        total + jumlahMenu(menu),
                      0
                    );

                    const totalHarga =
                      pesanan.totalHarga ??
                      pesanan.total_harga ??
                      pesanan.total ??
                      items.reduce(
                        (total, menu) =>
                          total +
                          hargaMenu(menu) * jumlahMenu(menu),
                        0
                      );

                    const nomorPesanan =
                      pesanan.nomorSimulasi ||
                      pesanan.kode ||
                      pesanan.id ||
                      `PES-${index + 1}`;

                    const namaTenant =
                      pesanan.namaTenant ||
                      pesanan.nama_tenant ||
                      pesanan.tenant?.nama ||
                      pesanan.tenant?.name ||
                      "Tenant KantinKita";

                    return (
                      <tr key={pesanan.id ?? nomorPesanan}>
                        <td className="riwayat-nomor">
                          {index + 1}
                        </td>

                        <td className="riwayat-id">
                          {nomorPesanan}
                        </td>

                        <td className="riwayat-tanggal">
                          {formatTanggal(
                            pesanan.tanggal ||
                            pesanan.created_at
                          )}
                        </td>

                        <td className="riwayat-tenant">
                          {namaTenant}
                        </td>

                        <td>
                          {items.length > 0 ? (
                            <div className="riwayat-menu-list">
                              {items.map((menu, menuIndex) => (
                                <div
                                  className="riwayat-menu-item"
                                  key={
                                    menu.id ??
                                    `${namaMenu(menu)}-${menuIndex}`
                                  }
                                >
                                  <span>
                                    {namaMenu(menu)}
                                  </span>

                                  <small>
                                    {jumlahMenu(menu)} item
                                    {" · "}
                                    {formatRupiah(
                                      hargaMenu(menu) *
                                      jumlahMenu(menu)
                                    )}
                                  </small>
                                </div>
                              ))}

                              <small>
                                Total {totalItem} item
                              </small>
                            </div>
                          ) : (
                            <span className="riwayat-tidak-ada">
                              Detail menu tidak tersedia
                            </span>
                          )}
                        </td>

                        <td className="riwayat-total">
                          {formatRupiah(totalHarga)}
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
          KantinKita · Riwayat transaksi pelanggan
        </footer>
      </div>
    </main>
  );
}