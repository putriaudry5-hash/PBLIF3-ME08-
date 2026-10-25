import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import "../../styles/PesananSaya.css";

const KEY_PESANAN = "kantinkita_pesanan";

const TAHAP_PESANAN = [
  {
    label: "Pembayaran Berhasil",
    deskripsi: "Pembayaran simulasi berhasil.",
    icon: "✓",
  },
  {
    label: "Sedang Diproses",
    deskripsi: "Pesanan sedang disiapkan.",
    icon: "♨",
  },
  {
    label: "Siap Diambil",
    deskripsi: "Silakan ambil pesanan di tenant.",
    icon: "▣",
  },
  {
    label: "Selesai",
    deskripsi: "Pesanan telah selesai.",
    icon: "✓",
  },
];

function bacaPesanan() {
  try {
    const data = localStorage.getItem(KEY_PESANAN);
    const daftar = data ? JSON.parse(data) : [];
    return Array.isArray(daftar) ? daftar : [];
  } catch {
    return [];
  }
}

function ambilStatusIndex(item) {
  const status = String(item.status || "").toLowerCase();

  if (status.includes("selesai")) return 3;

  if (
    status.includes("batal") ||
    status.includes("cancel")
  ) {
    return -1;
  }

  const index = Number(item.statusIndex);

  if (
    item.statusIndex !== undefined &&
    item.statusIndex !== null &&
    item.statusIndex !== "" &&
    Number.isFinite(index)
  ) {
    return Math.min(Math.max(index, 0), 3);
  }

  return 0;
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
    dateStyle: "medium",
    timeStyle: "short",
  });
}

function ambilNamaMenu(menu) {
  return (
    menu.nama ||
    menu.nama_menu ||
    menu.name ||
    menu.nama_produk ||
    "Menu"
  );
}

function ambilJumlah(menu) {
  return Number(
    menu.quantity ?? menu.jumlah ?? menu.qty ?? 1
  );
}

function ambilHarga(menu) {
  return Number(
    menu.harga ??
      menu.price ??
      menu.harga_satuan ??
      0
  );
}

export default function PesananSaya() {
  const navigate = useNavigate();
  const [pesanan, setPesanan] = useState([]);

  useEffect(() => {
    const muatPesanan = () => {
      setPesanan(bacaPesanan());
    };

    muatPesanan();

    window.addEventListener("focus", muatPesanan);
    window.addEventListener("storage", muatPesanan);
    window.addEventListener("pesanan-diperbarui", muatPesanan);

    return () => {
      window.removeEventListener("focus", muatPesanan);
      window.removeEventListener("storage", muatPesanan);
      window.removeEventListener("pesanan-diperbarui", muatPesanan);
    };
  }, []);

  // Pesanan selesai dan dibatalkan tidak ditampilkan di daftar aktif.
  const pesananAktif = pesanan.filter(
    (item) => ambilStatusIndex(item) >= 0 &&
      ambilStatusIndex(item) < 3
  );

  function ubahStatus(idPesanan, statusBaru) {
    const daftarTerbaru = bacaPesanan().map((item) => {
      const id = item.id || item.nomorSimulasi;

      if (String(id) !== String(idPesanan)) {
        return item;
      }

      return {
        ...item,
        statusIndex: statusBaru,
        status: TAHAP_PESANAN[statusBaru].label,
      };
    });

    try {
      localStorage.setItem(
        KEY_PESANAN,
        JSON.stringify(daftarTerbaru)
      );

      setPesanan(daftarTerbaru);

      // Memberi tahu komponen lain pada tab yang sama.
      window.dispatchEvent(new Event("pesanan-diperbarui"));
    } catch (error) {
      console.error("Gagal menyimpan status pesanan:", error);
    }
  }

  function lihatDetail(item) {
    navigate("/detail-pesanan", {
      state: {
        items: item.items || [],
        nomorSimulasi: item.nomorSimulasi || item.id,
        metode: item.metode || "QRIS",
        totalItem: item.totalItem,
        totalHarga: item.totalHarga,
        status: item.status,
        tanggal: item.tanggal,
      },
    });
  }

  return (
    <div className="pesanan-saya-page">
      <header className="pesanan-saya-header">
        <button
          type="button"
          className="pesanan-kembali"
          onClick={() => navigate("/beranda")}
        >
          ← Kembali
        </button>

        <h1>Pesanan Saya</h1>
        <p>Lihat dan pantau proses pesanan KantinKita kamu.</p>
      </header>

      {pesananAktif.length === 0 ? (
        <section className="pesanan-kosong">
          <div className="pesanan-kosong-icon">🛍️</div>
          <h2>Belum Ada Pesanan Aktif</h2>
          <p>
            Pesanan yang sedang diproses akan muncul di sini.
            Pesanan yang selesai dapat dilihat di Riwayat Pesanan.
          </p>

          <button
            type="button"
            className="pesanan-belanja-button"
            onClick={() => navigate("/beranda")}
          >
            Mulai Pesan
          </button>

          <button
            type="button"
            className="pesanan-detail-button"
            onClick={() => navigate("/riwayat-pesanan")}
          >
            Lihat Riwayat Pesanan →
          </button>
        </section>
      ) : (
        <section className="daftar-pesanan">
          <div className="daftar-pesanan-header">
            <h2>Pesanan Berlangsung</h2>
            <span>{pesananAktif.length} pesanan</span>
          </div>

          {pesananAktif.map((item, index) => {
            const idPesanan =
              item.id || item.nomorSimulasi || `pesanan-${index}`;

            const statusIndex = ambilStatusIndex(item);
            const tahapAktif = TAHAP_PESANAN[statusIndex];

            const items = Array.isArray(item.items)
              ? item.items
              : [];

            const jumlahTotal =
              item.totalItem ??
              items.reduce(
                (total, menu) => total + ambilJumlah(menu),
                0
              );

            return (
              <article className="pesanan-card" key={idPesanan}>
                <div className="pesanan-card-header">
                  <div>
                    <span className="pesanan-label">
                      NOMOR PESANAN
                    </span>
                    <h3>{item.nomorSimulasi || idPesanan}</h3>
                    <p className="pesanan-tanggal">
                      {formatTanggal(item.tanggal)}
                    </p>
                  </div>

                  <span className="pesanan-status">
                    {tahapAktif.label}
                  </span>
                </div>

                <div className="progres-pesanan">
                  <h3>Proses Pesanan</h3>

                  <div className="progres-langkah">
                    {TAHAP_PESANAN.map((tahap, tahapIndex) => (
                      <div
                        className={`progres-tahap ${
                          tahapIndex <= statusIndex ? "aktif" : ""
                        }`}
                        key={tahap.label}
                      >
                        <div className="progres-ikon">
                          {tahap.icon}
                        </div>
                        <span>{tahap.label}</span>
                      </div>
                    ))}
                  </div>

                  <div className="progres-garis">
                    <div
                      className="progres-garis-isi"
                      style={{
                        width: `${(statusIndex / 3) * 100}%`,
                      }}
                    />
                  </div>

                  <p className="progres-keterangan">
                    {tahapAktif.deskripsi}
                  </p>
                </div>

                <div className="pesanan-card-menu">
                  {items.map((menu, menuIndex) => (
                    <div
                      className="pesanan-menu-item"
                      key={
                        menu.id ||
                        menu.menu_id ||
                        menuIndex
                      }
                    >
                      <div>
                        <strong>{ambilNamaMenu(menu)}</strong>
                        <p>
                          {ambilJumlah(menu)} item ×{" "}
                          {formatRupiah(ambilHarga(menu))}
                        </p>
                      </div>

                      <strong>
                        {formatRupiah(
                          ambilJumlah(menu) * ambilHarga(menu)
                        )}
                      </strong>
                    </div>
                  ))}
                </div>

                <div className="pesanan-card-footer">
                  <div className="pesanan-ringkasan">
                    <span>Metode Pembayaran</span>
                    <strong>{item.metode || "QRIS"}</strong>

                    <span>Total Item</span>
                    <strong>{jumlahTotal} item</strong>

                    <span>Total Pembayaran</span>
                    <strong className="pesanan-total">
                      {formatRupiah(item.totalHarga)}
                    </strong>
                  </div>

                  <button
                    type="button"
                    className="pesanan-detail-button"
                    onClick={() => lihatDetail(item)}
                  >
                    Lihat Detail Pesanan →
                  </button>

                  <button
                    type="button"
                    className="pesanan-simulasi-button"
                    onClick={() =>
                      ubahStatus(idPesanan, statusIndex + 1)
                    }
                  >
                    Simulasikan:{" "}
                    {TAHAP_PESANAN[statusIndex + 1].label} →
                  </button>
                </div>
              </article>
            );
          })}
        </section>
      )}
    </div>
  );
}