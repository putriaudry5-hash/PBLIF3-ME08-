
import { useMemo, useState } from "react";
import {
  Wallet,
  CreditCard,
  Banknote,
  UtensilsCrossed,
  Download,
  Search,
  BarChart3,
  ReceiptText
} from "lucide-react";

import "../../styles/Tenant/LaporanTenant.css";

// ======================================
// DATA CONTOH TRANSAKSI TENANT
// ======================================

const dataTransaksi = [
  {
    id: "TRX001",
    tanggal: "2026-10-20",
    pelanggan: "Andi Saputra",
    jenis: "Online",
    metode: "QRIS",
    menu: "Ayam Geprek",
    kategori: "Satuan",
    jumlah: 2,
    total: 36000,
    status: "Lunas"
  },
  {
    id: "TRX002",
    tanggal: "2026-10-20",
    pelanggan: "Siti Amalia",
    jenis: "Online",
    metode: "QRIS",
    menu: "Nasi Goreng",
    kategori: "Satuan",
    jumlah: 1,
    total: 15000,
    status: "Lunas"
  },
  {
    id: "TRX003",
    tanggal: "2026-10-20",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Paket Prasmanan",
    kategori: "Prasmanan",
    jumlah: 2,
    total: 10000,
    status: "Lunas"
  },
  {
    id: "TRX004",
    tanggal: "2026-10-19",
    pelanggan: "Dewi Lestari",
    jenis: "Online",
    metode: "QRIS",
    menu: "Mie Goreng",
    kategori: "Satuan",
    jumlah: 2,
    total: 26000,
    status: "Lunas"
  },
  {
    id: "TRX005",
    tanggal: "2026-10-19",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Ayam Geprek",
    kategori: "Satuan",
    jumlah: 1,
    total: 18000,
    status: "Lunas"
  },
  {
    id: "TRX006",
    tanggal: "2026-10-18",
    pelanggan: "Nadia Putri",
    jenis: "Online",
    metode: "QRIS",
    menu: "Nasi Goreng",
    kategori: "Satuan",
    jumlah: 2,
    total: 30000,
    status: "Lunas"
  },
  {
    id: "TRX007",
    tanggal: "2026-10-18",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Paket Prasmanan",
    kategori: "Prasmanan",
    jumlah: 3,
    total: 12000,
    status: "Lunas"
  },
  {
    id: "TRX008",
    tanggal: "2026-10-17",
    pelanggan: "Rina Puspita",
    jenis: "Online",
    metode: "QRIS",
    menu: "Ayam Geprek",
    kategori: "Satuan",
    jumlah: 2,
    total: 36000,
    status: "Lunas"
  },
  {
    id: "TRX009",
    tanggal: "2026-10-16",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Mie Goreng",
    kategori: "Satuan",
    jumlah: 2,
    total: 26000,
    status: "Lunas"
  },
  {
    id: "TRX010",
    tanggal: "2026-10-15",
    pelanggan: "Fajar Pratama",
    jenis: "Online",
    metode: "QRIS",
    menu: "Nasi Goreng",
    kategori: "Satuan",
    jumlah: 2,
    total: 30000,
    status: "Lunas"
  },
  {
    id: "TRX011",
    tanggal: "2026-10-14",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Paket Prasmanan",
    kategori: "Prasmanan",
    jumlah: 2,
    total: 8000,
    status: "Lunas"
  },
  {
    id: "TRX012",
    tanggal: "2026-10-13",
    pelanggan: "Budi Santoso",
    jenis: "Online",
    metode: "QRIS",
    menu: "Ayam Geprek",
    kategori: "Satuan",
    jumlah: 1,
    total: 18000,
    status: "Lunas"
  },
  {
    id: "TRX013",
    tanggal: "2026-10-12",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Paket Prasmanan",
    kategori: "Prasmanan",
    jumlah: 2,
    total: 10000,
    status: "Lunas"
  },
  {
    id: "TRX014",
    tanggal: "2026-10-20",
    pelanggan: "Pelanggan Offline",
    jenis: "Offline",
    metode: "Tunai",
    menu: "Ayam Bakar",
    kategori: "Satuan",
    jumlah: 1,
    total: 20000,
    status: "Menunggu Kasir"
  }
];

const TANGGAL_CONTOH = "2026-10-20";

// ======================================
// FUNGSI BANTUAN
// ======================================

function rupiah(nilai) {
  return `Rp ${Number(nilai).toLocaleString("id-ID")}`;
}

function tanggalIndonesia(tanggal) {
  return new Date(`${tanggal}T12:00:00`).toLocaleDateString(
    "id-ID",
    {
      day: "2-digit",
      month: "short",
      year: "numeric"
    }
  );
}

function rentangMulai(periode) {
  if (periode === "Semua") return "";

  const akhir = new Date(`${TANGGAL_CONTOH}T12:00:00`);

  akhir.setDate(
    akhir.getDate() - (periode === "7 Hari" ? 6 : 29)
  );

  return [
    akhir.getFullYear(),
    String(akhir.getMonth() + 1).padStart(2, "0"),
    String(akhir.getDate()).padStart(2, "0")
  ].join("-");
}

// ======================================
// GRAFIK PENDAPATAN
// ======================================

function GrafikPendapatan({ transaksi, periode }) {
  const data = useMemo(() => {
    const hasil = new Map();

    transaksi.forEach((item) => {
      hasil.set(
        item.tanggal,
        (hasil.get(item.tanggal) || 0) + item.total
      );
    });

    const awal = rentangMulai(periode);
    const tanggal = [];

    if (periode !== "Semua") {
      const mulai = new Date(`${awal}T12:00:00`);
      const akhir = new Date(
        `${TANGGAL_CONTOH}T12:00:00`
      );

      for (
        let hari = new Date(mulai);
        hari <= akhir;
        hari.setDate(hari.getDate() + 1)
      ) {
        const kunci = [
          hari.getFullYear(),
          String(hari.getMonth() + 1).padStart(2, "0"),
          String(hari.getDate()).padStart(2, "0")
        ].join("-");

        tanggal.push(kunci);
      }
    } else {
      tanggal.push(...[...hasil.keys()].sort());
    }

    return tanggal.map((tanggalItem) => ({
      tanggal: tanggalItem,
      total: hasil.get(tanggalItem) || 0
    }));
  }, [transaksi, periode]);

  const lebar = 760;
  const tinggi = 240;
  const kiri = 70;
  const kanan = 22;
  const atas = 20;
  const bawah = 42;

  const ruangLebar = lebar - kiri - kanan;
  const ruangTinggi = tinggi - atas - bawah;

  const terbesar = Math.max(
    10000,
    ...data.map((item) => item.total)
  );

  const batasAtas =
    Math.ceil(terbesar / 10000) * 10000;

  const titik = data.map((item, index) => ({
    x:
      kiri +
      (data.length === 1
        ? ruangLebar / 2
        : (index / (data.length - 1)) * ruangLebar),
    y:
      atas +
      ruangTinggi -
      (item.total / batasAtas) * ruangTinggi,
    ...item
  }));

  const garis = titik
    .map((item) => `${item.x},${item.y}`)
    .join(" ");

  const labelLangkah = Math.max(
    1,
    Math.ceil(data.length / 7)
  );

  return (
    <div className="lt-chart-wrap">
      <svg
        className="lt-chart"
        viewBox={`0 0 ${lebar} ${tinggi}`}
        role="img"
        aria-label="Grafik garis pendapatan harian"
      >
        {[0, 1, 2, 3, 4].map((index) => {
          const y =
            atas + (index / 4) * ruangTinggi;

          const nilai =
            batasAtas * (1 - index / 4);

          return (
            <g key={index}>
              <line
                x1={kiri}
                x2={lebar - kanan}
                y1={y}
                y2={y}
                stroke="#eee8e4"
                strokeDasharray="4 4"
              />

              <text
                x={kiri - 10}
                y={y + 4}
                textAnchor="end"
                fill="#91848a"
                fontSize="11"
              >
                {nilai === 0
                  ? "0"
                  : `${Math.round(nilai / 1000)}rb`}
              </text>
            </g>
          );
        })}

        {titik.length > 1 && (
          <polyline
            points={garis}
            fill="none"
            stroke="#800e20"
            strokeWidth="3"
            strokeLinejoin="round"
            strokeLinecap="round"
          />
        )}

        {titik.map((item, index) => (
          <g key={item.tanggal}>
            <circle
              cx={item.x}
              cy={item.y}
              r="4"
              fill="#800e20"
              stroke="#ffffff"
              strokeWidth="2"
            >
              <title>
                {tanggalIndonesia(item.tanggal)}
                {": "}
                {rupiah(item.total)}
              </title>
            </circle>

            {(index % labelLangkah === 0 ||
              index === titik.length - 1) && (
              <text
                x={item.x}
                y={tinggi - 15}
                textAnchor="middle"
                fill="#91848a"
                fontSize="10"
              >
                {item.tanggal.slice(8, 10)} Okt
              </text>
            )}
          </g>
        ))}
      </svg>
    </div>
  );
}

// ======================================
// HALAMAN LAPORAN TENANT
// ======================================

export default function LaporanTenant() {
  const [periode, setPeriode] = useState("7 Hari");
  const [jenis, setJenis] = useState("Semua");
  const [pencarian, setPencarian] = useState("");

  // Transaksi yang sudah lunas
  const transaksiLunas = useMemo(() => {
    const mulai = rentangMulai(periode);

    return dataTransaksi.filter(
      (item) =>
        item.status === "Lunas" &&
        (!mulai || item.tanggal >= mulai) &&
        item.tanggal <= TANGGAL_CONTOH
    );
  }, [periode]);

  const transaksiJenis = useMemo(() => {
    return transaksiLunas.filter(
      (item) =>
        jenis === "Semua" || item.jenis === jenis
    );
  }, [transaksiLunas, jenis]);

  const transaksiTampil = useMemo(() => {
    const kata = pencarian.toLowerCase().trim();

    return transaksiJenis.filter(
      (item) =>
        item.id.toLowerCase().includes(kata) ||
        item.pelanggan.toLowerCase().includes(kata) ||
        item.menu.toLowerCase().includes(kata)
    );
  }, [transaksiJenis, pencarian]);

  // ======================================
  // PERHITUNGAN LAPORAN
  // ======================================

  const pendapatan = transaksiJenis.reduce(
    (total, item) => total + item.total,
    0
  );

  const pendapatanOnline = transaksiJenis
    .filter((item) => item.jenis === "Online")
    .reduce((total, item) => total + item.total, 0);

  const pendapatanOffline = transaksiJenis
    .filter((item) => item.jenis === "Offline")
    .reduce((total, item) => total + item.total, 0);

  const pendapatanPrasmanan = transaksiJenis
    .filter((item) => item.kategori === "Prasmanan")
    .reduce((total, item) => total + item.total, 0);

  const jumlahTransaksi = transaksiJenis.length;

  const totalItem = transaksiJenis.reduce(
    (total, item) => total + item.jumlah,
    0
  );

  const rekapMenu = Object.values(
    transaksiJenis.reduce((hasil, item) => {
      const kunci = item.menu;

      if (!hasil[kunci]) {
        hasil[kunci] = {
          nama: item.menu,
          kategori: item.kategori,
          jumlah: 0,
          pendapatan: 0
        };
      }

      hasil[kunci].jumlah += item.jumlah;
      hasil[kunci].pendapatan += item.total;

      return hasil;
    }, {})
  ).sort((a, b) => b.pendapatan - a.pendapatan);

  // ======================================
  // EKSPOR CSV
  // ======================================

  function eksporCSV() {
    const kolom = [
      "Kode",
      "Tanggal",
      "Pelanggan",
      "Jenis",
      "Menu",
      "Kategori",
      "Jumlah",
      "Total",
      "Status"
    ];

    const baris = transaksiTampil.map((item) => [
      item.id,
      item.tanggal,
      item.pelanggan,
      item.jenis,
      item.menu,
      item.kategori,
      item.jumlah,
      item.total,
      item.status
    ]);

    const amanCSV = (nilai) => {
      let teks = String(nilai ?? "");

      if (/^[\s]*[=+\-@]/.test(teks)) {
        teks = `'${teks}`;
      }

      return `"${teks.replace(/"/g, '""')}"`;
    };

    const isi = [kolom, ...baris]
      .map((barisCSV) =>
        barisCSV.map(amanCSV).join(",")
      )
      .join("\r\n");

    const blob = new Blob(
      ["\uFEFF", isi],
      { type: "text/csv;charset=utf-8;" }
    );

    const alamat = URL.createObjectURL(blob);
    const tautan = document.createElement("a");

    tautan.href = alamat;
    tautan.download = "laporan-penjualan-tenant.csv";

    document.body.appendChild(tautan);
    tautan.click();
    tautan.remove();

    URL.revokeObjectURL(alamat);
  }

  // ======================================
  // TAMPILAN HALAMAN
  // ======================================

  return (
    <div className="lt-page">

      {/* HEADER TANPA TOMBOL EKSPOR */}
      <div className="lt-heading">
        <div>
          <h1>Laporan Penjualan</h1>
          <p>
            Pantau pendapatan dan rekap penjualan tenant.
          </p>
        </div>
      </div>

      {/* FILTER */}
      <div className="lt-filter-panel">

        <div>
          <span>Periode Laporan</span>

          <select
            value={periode}
            onChange={(event) =>
              setPeriode(event.target.value)
            }
          >
            <option value="7 Hari">7 Hari Terakhir</option>
            <option value="30 Hari">30 Hari Terakhir</option>
            <option value="Semua">Semua Data</option>
          </select>
        </div>

        <div>
          <span>Jenis Transaksi</span>

          <select
            value={jenis}
            onChange={(event) =>
              setJenis(event.target.value)
            }
          >
            <option value="Semua">Semua Transaksi</option>
            <option value="Online">Online</option>
            <option value="Offline">Offline</option>
          </select>
        </div>

        <p>
          Pratinjau menggunakan transaksi contoh
          hingga 20 Oktober 2026.
        </p>
      </div>

      {/* KARTU RINGKASAN */}
      <div className="lt-stats">

        <div className="lt-stat-card">
          <span className="lt-stat-icon maroon">
            <Wallet size={20} />
          </span>

          <div>
            <small>Total Pendapatan</small>
            <strong>{rupiah(pendapatan)}</strong>
            <span>{jumlahTransaksi} transaksi lunas</span>
          </div>
        </div>

        <div className="lt-stat-card">
          <span className="lt-stat-icon blue">
            <CreditCard size={20} />
          </span>

          <div>
            <small>Penjualan Online</small>
            <strong>{rupiah(pendapatanOnline)}</strong>
            <span>Pembayaran digital</span>
          </div>
        </div>

        <div className="lt-stat-card">
          <span className="lt-stat-icon yellow">
            <Banknote size={20} />
          </span>

          <div>
            <small>Penjualan Offline</small>
            <strong>{rupiah(pendapatanOffline)}</strong>
            <span>Dicatat kasir utama</span>
          </div>
        </div>

        <div className="lt-stat-card">
          <span className="lt-stat-icon green">
            <UtensilsCrossed size={20} />
          </span>

          <div>
            <small>Penjualan Prasmanan</small>
            <strong>{rupiah(pendapatanPrasmanan)}</strong>
            <span>Bagian dari total pendapatan</span>
          </div>
        </div>

      </div>

      {/* GRAFIK PENDAPATAN */}
      <section className="lt-panel lt-chart-panel">

        <div className="lt-panel-heading">
          <div>
            <h2>Grafik Pendapatan</h2>
            <p>Perkembangan penjualan per hari.</p>
          </div>

          <span className="lt-panel-tag">
            {periode}
          </span>
        </div>

        <GrafikPendapatan
          transaksi={transaksiJenis}
          periode={periode}
        />

        <div className="lt-chart-footer">
          <span className="lt-chart-legend" />
          Pendapatan transaksi lunas
        </div>

      </section>

      {/* RINGKASAN DAN REKAP MENU */}
      <div className="lt-bottom-grid">

        <section className="lt-panel">
          <div className="lt-panel-heading">
            <div>
              <h2>Ringkasan Penjualan</h2>
              <p>
                Jumlah transaksi dan makanan terjual.
              </p>
            </div>

            <BarChart3 size={17} />
          </div>

          <div className="lt-summary-list">

            <div>
              <span>Transaksi Lunas</span>
              <strong>{jumlahTransaksi}</strong>
            </div>

            <div>
              <span>Total Makanan Terjual</span>
              <strong>{totalItem}</strong>
            </div>

            <div>
              <span>Pendapatan Online</span>
              <strong>{rupiah(pendapatanOnline)}</strong>
            </div>

            <div>
              <span>Pendapatan Offline</span>
              <strong>{rupiah(pendapatanOffline)}</strong>
            </div>

          </div>
        </section>

        <section className="lt-panel">
          <div className="lt-panel-heading">
            <div>
              <h2>Rekap Menu Terjual</h2>
              <p>Menu berdasarkan pendapatan.</p>
            </div>

            <UtensilsCrossed size={17} />
          </div>

          <div className="lt-menu-recap">

            {rekapMenu.length === 0 ? (
              <p className="lt-empty-text">
                Belum ada penjualan.
              </p>
            ) : (
              rekapMenu.map((menu) => (
                <div key={menu.nama}>
                  <span>
                    <strong>{menu.nama}</strong>
                    <small>{menu.jumlah} porsi</small>
                  </span>

                  <strong>
                    {rupiah(menu.pendapatan)}
                  </strong>
                </div>
              ))
            )}

          </div>
        </section>
      </div>

      {/* DETAIL TRANSAKSI */}
      <section className="lt-panel">

        <div className="lt-panel-heading">
          <div>
            <h2>Detail Transaksi Penjualan</h2>
            <p>
              Transaksi lunas pada periode yang dipilih.
            </p>
          </div>

          <span className="lt-panel-tag">
            {transaksiTampil.length} transaksi
          </span>
        </div>

        <div className="lt-table-toolbar">
          <Search size={16} />

          <input
            type="search"
            placeholder="Cari transaksi atau menu..."
            value={pencarian}
            onChange={(event) =>
              setPencarian(event.target.value)
            }
          />
        </div>

        <div className="lt-table-wrap">
          <table className="lt-table">

            <thead>
              <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Menu</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody>
              {transaksiTampil.map((item) => (
                <tr key={item.id}>

                  <td className="lt-code">
                    #{item.id}
                  </td>

                  <td>
                    {tanggalIndonesia(item.tanggal)}
                  </td>

                  <td>{item.pelanggan}</td>

                  <td>{item.menu}</td>

                  <td>
                    <span
                      className={`lt-type ${
                        item.jenis.toLowerCase()
                      }`}
                    >
                      {item.jenis}
                    </span>
                  </td>

                  <td>{item.jumlah}</td>

                  <td className="lt-money">
                    {rupiah(item.total)}
                  </td>

                  <td>
                    <span className="lt-paid">
                      Lunas
                    </span>
                  </td>

                </tr>
              ))}
            </tbody>

          </table>

          {transaksiTampil.length === 0 && (
            <div className="lt-empty">
              <ReceiptText size={25} />
              <strong>
                Transaksi tidak ditemukan
              </strong>
              <p>
                Ubah periode, jenis, atau pencarian.
              </p>
            </div>
          )}
        </div>

      </section>

      {/* TOMBOL EKSPOR DI KANAN BAWAH */}
      <div className="lt-export-bottom">
        <button
          type="button"
          className="lt-export"
          onClick={eksporCSV}
        >
          <Download size={16} />
          Ekspor CSV
        </button>
      </div>

    </div>
  );
}
