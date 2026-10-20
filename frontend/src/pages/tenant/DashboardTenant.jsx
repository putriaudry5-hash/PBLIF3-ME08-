
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import {
  ShoppingCart,
  Clock3,
  ReceiptText,
  Wallet,
  ClipboardList,
  ArrowRight,
  ChevronRight,
  UtensilsCrossed,
  ChartNoAxesColumnIncreasing,
  Ellipsis,
  ChartColumnIncreasing,
  ListChecks,
  Settings2,
  Package,
  CircleCheckBig
} from "lucide-react";

import "../../styles/Tenant/DashboardTenant.css";

/* DATA CONTOH DASHBOARD */

const transaksi = [
  {
    kode: "#TRX001",
    waktu: "10:24",
    pelanggan: "Andi Saputra",
    total: 28000,
    jenis: "Online",
    status: "Pesanan Baru",
    warna: "baru"
  },
  {
    kode: "#TRX002",
    waktu: "10:15",
    pelanggan: "Siti Amalia",
    total: 35000,
    jenis: "Offline",
    status: "Diproses",
    warna: "diproses"
  },
  {
    kode: "#TRX003",
    waktu: "09:50",
    pelanggan: "Budi Santoso",
    total: 22000,
    jenis: "Online",
    status: "Siap Diambil",
    warna: "siap"
  },
  {
    kode: "#TRX004",
    waktu: "09:30",
    pelanggan: "Rina Puspita",
    total: 18000,
    jenis: "Offline",
    status: "Selesai",
    warna: "selesai"
  },
  {
    kode: "#TRX005",
    waktu: "09:12",
    pelanggan: "Dewi Lestari",
    total: 40000,
    jenis: "Online",
    status: "Diproses",
    warna: "diproses"
  }
];

const daftarStatus = [
  {
    nama: "Pesanan Baru",
    jumlah: 2,
    icon: ReceiptText,
    className: "baru"
  },
  {
    nama: "Diproses",
    jumlah: 4,
    icon: Settings2,
    className: "diproses"
  },
  {
    nama: "Siap Diambil",
    jumlah: 3,
    icon: Package,
    className: "siap"
  },
  {
    nama: "Selesai",
    jumlah: 3,
    icon: CircleCheckBig,
    className: "selesai"
  }
];

const grafikHariIni = [
  { jam: "08:00", nilai: 20 },
  { jam: "09:00", nilai: 50 },
  { jam: "10:00", nilai: 100 },
  { jam: "11:00", nilai: 120 },
  { jam: "12:00", nilai: 145 },
  { jam: "13:00", nilai: 85 },
  { jam: "14:00", nilai: 40 },
  { jam: "15:00", nilai: 20 }
];

const grafikKemarin = [
  { jam: "08:00", nilai: 15 },
  { jam: "09:00", nilai: 45 },
  { jam: "10:00", nilai: 90 },
  { jam: "11:00", nilai: 105 },
  { jam: "12:00", nilai: 130 },
  { jam: "13:00", nilai: 75 },
  { jam: "14:00", nilai: 55 },
  { jam: "15:00", nilai: 25 }
];

function formatRupiah(angka) {
  return `Rp ${angka.toLocaleString("id-ID")}`;
}

function DashboardTenant() {
  const navigate = useNavigate();

  const [onlineAktif, setOnlineAktif] = useState(true);
  const [periodeGrafik, setPeriodeGrafik] = useState("hari-ini");

  const dataGrafik =
    periodeGrafik === "hari-ini"
      ? grafikHariIni
      : grafikKemarin;

  const namaTenant = "Ayam Geprek Bu Rina";

  return (
    <div className="td-page">

      {/* JUDUL */}
      <div className="td-heading">
        <div>
          <h1>Dashboard Tenant</h1>
          <p>
            Ringkasan aktivitas penjualan hari ini di {namaTenant}.
          </p>
        </div>

        <button
          type="button"
          className={`td-online-toggle ${
            onlineAktif ? "aktif" : "nonaktif"
          }`}
          onClick={() => setOnlineAktif(!onlineAktif)}
          aria-pressed={onlineAktif}
          title="Status online saat ini hanya contoh tampilan"
        >
          <span className="td-online-dot" />
          {onlineAktif ? "Online" : "Nonaktif"}
        </button>
      </div>

      {/* EMPAT KARTU STATISTIK */}
      <div className="td-stats">

        <div className="td-stat-card">
          <div className="td-stat-icon kuning">
            <ShoppingCart />
          </div>

          <div className="td-stat-info">
            <span>Transaksi Hari Ini</span>
            <strong>12</strong>
            <small>6 Online • 6 Offline</small>
          </div>
        </div>

        <div className="td-stat-card">
          <div className="td-stat-icon merah">
            <Clock3 />
          </div>

          <div className="td-stat-info">
            <span>Pesanan Diproses</span>
            <strong>2</strong>
            <small>Pesanan sedang diproses</small>
          </div>
        </div>

        <div className="td-stat-card">
          <div className="td-stat-icon kuning">
            <ReceiptText />
          </div>

          <div className="td-stat-info">
            <span>Pesanan Online Masuk</span>
            <strong>5</strong>
            <small>Pesanan baru</small>
          </div>
        </div>

        <div className="td-stat-card">
          <div className="td-stat-icon merah">
            <Wallet />
          </div>

          <div className="td-stat-info">
            <span>Pendapatan Hari Ini</span>
            <strong>{formatRupiah(245000)}</strong>
            <small className="td-growth">
              <span>↗ +12%</span> dari kemarin
            </small>
          </div>
        </div>
      </div>

      {/* PANEL TRANSAKSI DAN AKSI CEPAT */}
      <div className="td-content-grid">

        {/* TRANSAKSI */}
        <section className="td-panel td-transactions">
          <div className="td-panel-heading">
            <div className="td-heading-left">
              <ClipboardList size={19} />
              <div>
                <h2>Transaksi Terbaru</h2>
                <p>Daftar transaksi terbaru dari pelanggan.</p>
              </div>
            </div>

            <button
              type="button"
              className="td-view-all"
              onClick={() =>
                navigate("/tenant/riwayat-transaksi")
              }
            >
              Lihat Semua
              <ArrowRight size={15} />
            </button>
          </div>

          <div className="td-table-wrap">
            <table className="td-table">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Waktu</th>
                  <th>Pelanggan</th>
                  <th>Total</th>
                  <th>Jenis</th>
                  <th>Status Pesanan</th>
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody>
                {transaksi.map((item) => (
                  <tr key={item.kode}>
                    <td className="td-code">{item.kode}</td>
                    <td>{item.waktu}</td>
                    <td>{item.pelanggan}</td>
                    <td>{formatRupiah(item.total)}</td>
                    <td>{item.jenis}</td>

                    <td>
                      <span
                        className={`td-status-badge ${item.warna}`}
                      >
                        {item.status}
                      </span>
                    </td>

                    <td>
                      <button
                        type="button"
                        className="td-table-action"
                        aria-label={`Lihat transaksi ${item.kode}`}
                        onClick={() =>
                          navigate("/tenant/riwayat-transaksi")
                        }
                      >
                        <Ellipsis size={17} />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>

        {/* AKSI CEPAT */}
        <section className="td-panel td-quick-panel">
          <div className="td-panel-heading">
            <div className="td-heading-left">
              <ChartColumnIncreasing size={19} />
              <div>
                <h2>Aksi Cepat</h2>
                <p>Menu yang sering digunakan.</p>
              </div>
            </div>
          </div>

          <div className="td-quick-list">

            <button
              type="button"
              className="td-quick-item pesanan"
              onClick={() =>
                navigate("/tenant/pesanan-online")
              }
            >
              <span className="td-quick-icon">
                <ClipboardList size={19} />
              </span>

              <span className="td-quick-text">
                <strong>Lihat Pesanan Masuk</strong>
                <small>3 pesanan baru</small>
              </span>

              <ChevronRight size={17} />
            </button>

            <button
              type="button"
              className="td-quick-item menu"
              onClick={() => navigate("/tenant/menu")}
            >
              <span className="td-quick-icon">
                <UtensilsCrossed size={19} />
              </span>

              <span className="td-quick-text">
                <strong>Kelola Menu</strong>
                <small>Tambah, ubah, atau stok menu</small>
              </span>

              <ChevronRight size={17} />
            </button>

            <button
              type="button"
              className="td-quick-item laporan"
              onClick={() => navigate("/tenant/laporan")}
            >
              <span className="td-quick-icon">
                <ChartNoAxesColumnIncreasing size={19} />
              </span>

              <span className="td-quick-text">
                <strong>Laporan Penjualan</strong>
                <small>Lihat rekap penjualan</small>
              </span>

              <ChevronRight size={17} />
            </button>
          </div>
        </section>
      </div>

      {/* PANEL BAWAH */}
      <div className="td-bottom-grid">

        {/* GRAFIK PENJUALAN */}
        <section className="td-panel td-chart-panel">
          <div className="td-panel-heading">
            <div className="td-heading-left">
              <ChartColumnIncreasing
                className="td-chart-heading-icon"
                size={19}
              />

              <div>
                <h2>Grafik Penjualan Hari Ini</h2>
                <p>Total penjualan per jam.</p>
              </div>
            </div>

            <select
              className="td-chart-select"
              aria-label="Pilih periode grafik"
              value={periodeGrafik}
              onChange={(event) =>
                setPeriodeGrafik(event.target.value)
              }
            >
              <option value="hari-ini">Hari Ini</option>
              <option value="kemarin">Kemarin</option>
            </select>
          </div>

          <div className="td-chart">
            <div className="td-chart-yaxis">
              <span>150k</span>
              <span>100k</span>
              <span>50k</span>
              <span>0</span>
            </div>

            <div className="td-chart-main">
              <div className="td-chart-gridlines">
                <span />
                <span />
                <span />
                <span />
              </div>

              <div className="td-chart-bars">
                {dataGrafik.map((item) => (
                  <div
                    key={item.jam}
                    className="td-chart-column"
                  >
                    <div className="td-chart-bar-track">
                      <div
                        className="td-chart-bar"
                        style={{
                          height: `${(item.nilai / 150) * 100}%`
                        }}
                        title={`${item.jam}: Rp ${item.nilai}.000`}
                      />
                    </div>

                    <span>{item.jam}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        {/* STATUS PESANAN TANPA PERSENTASE */}
        <section className="td-panel td-order-panel">
          <div className="td-panel-heading">
            <div className="td-heading-left">
              <ListChecks size={19} />

              <div>
                <h2>Status Pesanan Hari Ini</h2>
                <p>Jumlah pesanan berdasarkan status.</p>
              </div>
            </div>
          </div>

          <div className="td-status-grid">
            {daftarStatus.map((item) => {
              const Icon = item.icon;

              return (
                <div
                  key={item.nama}
                  className={`td-order-status ${item.className}`}
                >
                  <Icon size={22} strokeWidth={1.8} />
                  <strong>{item.jumlah}</strong>
                  <span>{item.nama}</span>
                </div>
              );
            })}
          </div>
        </section>
      </div>
    </div>
  );
}

export default DashboardTenant;
