
import { useMemo, useState } from "react";
import {
  Search,
  ReceiptText,
  CreditCard,
  Banknote,
  CircleCheck,
  Eye,
  X
} from "lucide-react";

import "../../styles/Tenant/RiwayatTransaksiTenant.css";

// ======================================
// DATA CONTOH TRANSAKSI TENANT
// ======================================

const dataTransaksi = [
  {
    id: "TRX001",
    tanggal: "20 Okt 2026",
    waktu: "10:24",
    pelanggan: "Andi Saputra",
    jenis: "Online",
    metode: "QRIS",
    status: "Lunas",
    items: [
      { nama: "Ayam Geprek", jumlah: 2, harga: 18000 }
    ]
  },
  {
    id: "TRX002",
    tanggal: "20 Okt 2026",
    waktu: "10:18",
    pelanggan: "Siti Amalia",
    jenis: "Online",
    metode: "QRIS",
    status: "Lunas",
    items: [
      { nama: "Nasi Goreng", jumlah: 1, harga: 15000 },
      { nama: "Telur Balado", jumlah: 1, harga: 5000 }
    ]
  },
  {
    id: "TRX003",
    tanggal: "20 Okt 2026",
    waktu: "10:12",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    status: "Lunas",
    items: [
      { nama: "Paket Prasmanan", jumlah: 1, harga: 5000 },
      { nama: "Ayam Geprek", jumlah: 1, harga: 18000 }
    ]
  },
  {
    id: "TRX004",
    tanggal: "20 Okt 2026",
    waktu: "09:55",
    pelanggan: "Dewi Lestari",
    jenis: "Online",
    metode: "QRIS",
    status: "Lunas",
    items: [
      { nama: "Mie Goreng", jumlah: 2, harga: 13000 }
    ]
  },
  {
    id: "TRX005",
    tanggal: "20 Okt 2026",
    waktu: "09:40",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    status: "Lunas",
    items: [
      { nama: "Ayam Geprek", jumlah: 1, harga: 18000 }
    ]
  },
  {
    id: "TRX006",
    tanggal: "19 Okt 2026",
    waktu: "15:20",
    pelanggan: "Nadia Putri",
    jenis: "Online",
    metode: "QRIS",
    status: "Lunas",
    items: [
      { nama: "Nasi Goreng", jumlah: 1, harga: 15000 }
    ]
  },
  {
    id: "TRX007",
    tanggal: "19 Okt 2026",
    waktu: "13:45",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    status: "Lunas",
    items: [
      { nama: "Paket Prasmanan", jumlah: 3, harga: 4000 }
    ]
  },
  {
    id: "TRX008",
    tanggal: "19 Okt 2026",
    waktu: "12:15",
    pelanggan: "Rina Puspita",
    jenis: "Online",
    metode: "QRIS",
    status: "Lunas",
    items: [
      { nama: "Ayam Geprek", jumlah: 2, harga: 18000 }
    ]
  },
  {
    id: "TRX009",
    tanggal: "18 Okt 2026",
    waktu: "11:30",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    status: "Lunas",
    items: [
      { nama: "Mie Goreng", jumlah: 1, harga: 13000 },
      { nama: "Telur Balado", jumlah: 1, harga: 5000 }
    ]
  },
  {
    id: "TRX010",
    tanggal: "18 Okt 2026",
    waktu: "10:05",
    pelanggan: "Fajar Pratama",
    jenis: "Online",
    metode: "QRIS",
    status: "Lunas",
    items: [
      { nama: "Nasi Goreng", jumlah: 2, harga: 15000 }
    ]
  }
];

// ======================================
// FUNGSI BANTUAN
// ======================================

function rupiah(nilai) {
  return `Rp ${Number(nilai).toLocaleString("id-ID")}`;
}

function hitungTotal(transaksi) {
  return transaksi.items.reduce(
    (total, item) => total + item.harga * item.jumlah,
    0
  );
}

function hitungJumlah(transaksi) {
  return transaksi.items.reduce(
    (total, item) => total + item.jumlah,
    0
  );
}

// ======================================
// KOMPONEN HALAMAN
// ======================================

export default function RiwayatTransaksiTenant() {
  const [pencarian, setPencarian] = useState("");
  const [jenis, setJenis] = useState("Semua");
  const [status, setStatus] = useState("Semua");
  const [detailId, setDetailId] = useState(null);

  // Statistik berdasarkan transaksi lunas
  const totalTransaksi = dataTransaksi.length;

  const totalOnline = dataTransaksi.filter(
    (item) => item.jenis === "Online"
  ).length;

  const totalOffline = dataTransaksi.filter(
    (item) => item.jenis === "Offline"
  ).length;

  const pendapatan = dataTransaksi
    .filter((item) => item.status === "Lunas")
    .reduce(
      (total, item) => total + hitungTotal(item),
      0
    );

  // Pencarian dan filter
  const transaksiTampil = useMemo(() => {
    const kata = pencarian.toLowerCase().trim();

    return dataTransaksi.filter((item) => {
      const cocokCari =
        item.id.toLowerCase().includes(kata) ||
        item.pelanggan.toLowerCase().includes(kata) ||
        item.items.some((menu) =>
          menu.nama.toLowerCase().includes(kata)
        );

      const cocokJenis =
        jenis === "Semua" || item.jenis === jenis;

      const cocokStatus =
        status === "Semua" || item.status === status;

      return cocokCari && cocokJenis && cocokStatus;
    });
  }, [pencarian, jenis, status]);

  const detail = dataTransaksi.find(
    (item) => item.id === detailId
  );

  return (
    <div className="rt-page">

      {/* HEADER */}
      <div className="rt-heading">
        <h1>Riwayat Transaksi</h1>
        <p>
          Lihat riwayat transaksi online dan offline
          yang tercatat untuk tenant Anda.
        </p>
      </div>

      {/* KARTU RINGKASAN */}
      <div className="rt-stats">

        <div className="rt-stat-card">
          <span className="rt-stat-icon kuning">
            <ReceiptText size={20} />
          </span>
          <div>
            <span>Total Transaksi</span>
            <strong>{totalTransaksi}</strong>
            <small>Seluruh transaksi</small>
          </div>
        </div>

        <div className="rt-stat-card">
          <span className="rt-stat-icon biru">
            <CreditCard size={20} />
          </span>
          <div>
            <span>Transaksi Online</span>
            <strong>{totalOnline}</strong>
            <small>Pembayaran digital</small>
          </div>
        </div>

        <div className="rt-stat-card">
          <span className="rt-stat-icon merah">
            <Banknote size={20} />
          </span>
          <div>
            <span>Transaksi Offline</span>
            <strong>{totalOffline}</strong>
            <small>Pembayaran kasir utama</small>
          </div>
        </div>

        <div className="rt-stat-card">
          <span className="rt-stat-icon hijau">
            <CircleCheck size={20} />
          </span>
          <div>
            <span>Total Pendapatan</span>
            <strong className="rt-stat-money">
              {rupiah(pendapatan)}
            </strong>
            <small>Transaksi lunas</small>
          </div>
        </div>

      </div>

      {/* DAFTAR RIWAYAT */}
      <section className="rt-panel">

        <div className="rt-panel-heading">
          <div>
            <h2>Daftar Riwayat Transaksi</h2>
            <p>
              Semua transaksi yang tercatat untuk tenant.
            </p>
          </div>

          <span className="rt-total-label">
            {transaksiTampil.length} transaksi
          </span>
        </div>

        {/* FILTER */}
        <div className="rt-toolbar">

          <label className="rt-search">
            <Search size={17} />
            <input
              type="search"
              value={pencarian}
              placeholder="Cari kode, pelanggan, atau menu..."
              onChange={(e) =>
                setPencarian(e.target.value)
              }
            />
          </label>

          <select
            className="rt-filter"
            value={jenis}
            aria-label="Filter jenis transaksi"
            onChange={(e) => setJenis(e.target.value)}
          >
            <option value="Semua">Semua Jenis</option>
            <option value="Online">Online</option>
            <option value="Offline">Offline</option>
          </select>

          <select
            className="rt-filter"
            value={status}
            aria-label="Filter status pembayaran"
            onChange={(e) => setStatus(e.target.value)}
          >
            <option value="Semua">Semua Status</option>
            <option value="Lunas">Lunas</option>
            <option value="Menunggu">Menunggu</option>
            <option value="Dibatalkan">Dibatalkan</option>
          </select>

        </div>

        {/* TABEL */}
        <div className="rt-table-wrap">
          <table className="rt-table">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Jenis</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              {transaksiTampil.map((item) => (
                <tr key={item.id}>

                  <td className="rt-code">
                    #{item.id}
                  </td>

                  <td>
                    <div className="rt-date">
                      <strong>{item.tanggal}</strong>
                      <small>{item.waktu}</small>
                    </div>
                  </td>

                  <td>
                    <div className="rt-customer">
                      <strong>{item.pelanggan}</strong>
                      <small>
                        {hitungJumlah(item)} item
                      </small>
                    </div>
                  </td>

                  <td>
                    <span
                      className={`rt-type ${item.jenis.toLowerCase()}`}
                    >
                      {item.jenis}
                    </span>
                  </td>

                  <td className="rt-money">
                    {rupiah(hitungTotal(item))}
                  </td>

                  <td>{item.metode}</td>

                  <td>
                    <span
                      className={`rt-status ${item.status.toLowerCase()}`}
                    >
                      {item.status}
                    </span>
                  </td>

                  <td>
                    <button
                      type="button"
                      className="rt-detail-btn"
                      onClick={() => setDetailId(item.id)}
                    >
                      <Eye size={15} />
                      Detail
                    </button>
                  </td>

                </tr>
              ))}
            </tbody>
          </table>

          {transaksiTampil.length === 0 && (
            <div className="rt-empty">
              <ReceiptText size={27} />
              <strong>Transaksi tidak ditemukan</strong>
              <p>
                Coba ubah pencarian atau filter.
              </p>
            </div>
          )}

        </div>
      </section>

      {/* MODAL DETAIL */}
      {detail && (
        <div
          className="rt-backdrop"
          onMouseDown={(e) => {
            if (e.target === e.currentTarget) {
              setDetailId(null);
            }
          }}
        >
          <div
            className="rt-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="rt-detail-title"
          >
            <div className="rt-modal-header">
              <div>
                <h2 id="rt-detail-title">
                  Detail Transaksi
                </h2>
                <p>#{detail.id}</p>
              </div>

              <button
                type="button"
                className="rt-close"
                aria-label="Tutup detail"
                onClick={() => setDetailId(null)}
              >
                <X size={18} />
              </button>
            </div>

            <div className="rt-modal-body">

              <div className="rt-detail-badges">
                <span
                  className={`rt-type ${detail.jenis.toLowerCase()}`}
                >
                  {detail.jenis}
                </span>

                <span
                  className={`rt-status ${detail.status.toLowerCase()}`}
                >
                  {detail.status}
                </span>
              </div>

              <div className="rt-detail-info">
                <div>
                  <span>Pelanggan</span>
                  <strong>{detail.pelanggan}</strong>
                </div>
                <div>
                  <span>Tanggal dan Waktu</span>
                  <strong>
                    {detail.tanggal}, {detail.waktu}
                  </strong>
                </div>
                <div>
                  <span>Jenis Transaksi</span>
                  <strong>{detail.jenis}</strong>
                </div>
                <div>
                  <span>Metode Pembayaran</span>
                  <strong>{detail.metode}</strong>
                </div>
              </div>

              <div className="rt-detail-section">
                <h3>Rincian Makanan</h3>

                {detail.items.map((menu, index) => (
                  <div
                    key={`${menu.nama}-${index}`}
                    className="rt-detail-item"
                  >
                    <div>
                      <strong>{menu.nama}</strong>
                      <small>
                        {menu.jumlah} × {rupiah(menu.harga)}
                      </small>
                    </div>

                    <strong>
                      {rupiah(menu.jumlah * menu.harga)}
                    </strong>
                  </div>
                ))}
              </div>

              <div className="rt-grand-total">
                <span>Total Pembayaran</span>
                <strong>
                  {rupiah(hitungTotal(detail))}
                </strong>
              </div>

              {detail.jenis === "Offline" && (
                <p className="rt-detail-note">
                  Pembayaran tunai dicatat oleh Kasir Utama.
                </p>
              )}

              <p className="rt-preview-note">
                Data ini merupakan contoh frontend.
              </p>
            </div>

            <div className="rt-modal-footer">
              <button
                type="button"
                className="rt-close-btn"
                onClick={() => setDetailId(null)}
              >
                Tutup
              </button>
            </div>

          </div>
        </div>
      )}

    </div>
  );
}
