
import { useMemo, useState } from "react";
import {
  Search,
  ClipboardList,
  CookingPot,
  CircleCheck,
  PackageCheck,
  Eye,
  ArrowRight,
  X,
  UserRound,
  CalendarDays,
  Wallet,
  AlertCircle
} from "lucide-react";

import "../../styles/Tenant/PesananOnlineTenant.css";

/* =========================================
   DATA CONTOH PESANAN ONLINE
========================================= */

const dataPesanan = [
  {
    id: "PSN001",
    pelanggan: "Andi Saputra",
    waktu: "10:24",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Baru",
    catatan: "Sambal dipisah.",
    items: [
      {
        nama: "Ayam Geprek",
        detail: "Level 2",
        jumlah: 2,
        harga: 18000
      }
    ]
  },
  {
    id: "PSN002",
    pelanggan: "Siti Amalia",
    waktu: "10:18",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Baru",
    catatan: "",
    items: [
      {
        nama: "Nasi Goreng",
        detail: "",
        jumlah: 1,
        harga: 15000
      },
      {
        nama: "Telur Balado",
        detail: "",
        jumlah: 1,
        harga: 5000
      }
    ]
  },
  {
    id: "PSN003",
    pelanggan: "Budi Santoso",
    waktu: "10:10",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Baru",
    catatan: "Tidak pedas.",
    items: [
      {
        nama: "Paket Prasmanan",
        detail: "Porsi Rp5.000",
        jumlah: 2,
        harga: 5000
      },
      {
        nama: "Ayam Geprek",
        detail: "",
        jumlah: 1,
        harga: 18000
      }
    ]
  },
  {
    id: "PSN004",
    pelanggan: "Dewi Lestari",
    waktu: "09:55",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Diproses",
    catatan: "",
    items: [
      {
        nama: "Mie Goreng",
        detail: "",
        jumlah: 2,
        harga: 13000
      }
    ]
  },
  {
    id: "PSN005",
    pelanggan: "Rudi Hartono",
    waktu: "09:40",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Diproses",
    catatan: "Dibungkus.",
    items: [
      {
        nama: "Ayam Geprek",
        detail: "",
        jumlah: 1,
        harga: 18000
      }
    ]
  },
  {
    id: "PSN006",
    pelanggan: "Nadia Putri",
    waktu: "09:20",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Siap Diambil",
    catatan: "",
    items: [
      {
        nama: "Nasi Goreng",
        detail: "",
        jumlah: 1,
        harga: 15000
      }
    ]
  },
  {
    id: "PSN007",
    pelanggan: "Fajar Pratama",
    waktu: "08:55",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Selesai",
    catatan: "",
    items: [
      {
        nama: "Ayam Geprek",
        detail: "",
        jumlah: 2,
        harga: 18000
      }
    ]
  },
  {
    id: "PSN008",
    pelanggan: "Rina Puspita",
    waktu: "08:40",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Selesai",
    catatan: "",
    items: [
      {
        nama: "Paket Prasmanan",
        detail: "Porsi Rp4.000",
        jumlah: 2,
        harga: 4000
      }
    ]
  },
  {
    id: "PSN009",
    pelanggan: "Agus Firmansyah",
    waktu: "08:20",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Selesai",
    catatan: "",
    items: [
      {
        nama: "Mie Goreng",
        detail: "",
        jumlah: 1,
        harga: 13000
      }
    ]
  },
  {
    id: "PSN010",
    pelanggan: "Lina Marlina",
    waktu: "08:05",
    tanggal: "20 Okt 2026",
    metode: "QRIS",
    pembayaran: "Lunas",
    status: "Selesai",
    catatan: "",
    items: [
      {
        nama: "Nasi Goreng",
        detail: "",
        jumlah: 2,
        harga: 15000
      }
    ]
  }
];

/* =========================================
   KONFIGURASI STATUS
========================================= */

const daftarStatus = [
  "Semua",
  "Baru",
  "Diproses",
  "Siap Diambil",
  "Selesai"
];

const langkahBerikutnya = {
  Baru: "Diproses",
  Diproses: "Siap Diambil",
  "Siap Diambil": "Selesai"
};

const labelAksi = {
  Baru: "Proses Pesanan",
  Diproses: "Tandai Siap Diambil",
  "Siap Diambil": "Selesaikan Pesanan"
};

const kelasStatus = {
  Baru: "baru",
  Diproses: "diproses",
  "Siap Diambil": "siap",
  Selesai: "selesai"
};

const kartuStatus = [
  {
    status: "Baru",
    label: "Pesanan Baru",
    icon: ClipboardList,
    warna: "kuning"
  },
  {
    status: "Diproses",
    label: "Diproses",
    icon: CookingPot,
    warna: "merah"
  },
  {
    status: "Siap Diambil",
    label: "Siap Diambil",
    icon: PackageCheck,
    warna: "biru"
  },
  {
    status: "Selesai",
    label: "Selesai",
    icon: CircleCheck,
    warna: "hijau"
  }
];

/* =========================================
   FUNGSI BANTUAN
========================================= */

function rupiah(nilai) {
  return `Rp ${Number(nilai).toLocaleString("id-ID")}`;
}

function totalPesanan(pesanan) {
  return pesanan.items.reduce(
    (total, item) => total + item.jumlah * item.harga,
    0
  );
}

function jumlahItem(pesanan) {
  return pesanan.items.reduce(
    (total, item) => total + item.jumlah,
    0
  );
}

/* =========================================
   BADGE STATUS
   Diletakkan di luar komponen utama
========================================= */

function BadgeStatus({ status }) {
  return (
    <span
      className={`po-status ${
        kelasStatus[status] || "baru"
      }`}
    >
      <span className="po-status-dot" />
      {status}
    </span>
  );
}

/* =========================================
   HALAMAN PESANAN ONLINE TENANT
========================================= */

export default function PesananOnlineTenant() {
  const [pesanan, setPesanan] = useState(dataPesanan);
  const [pencarian, setPencarian] = useState("");
  const [filter, setFilter] = useState("Semua");

  const [detailId, setDetailId] = useState(null);
  const [konfirmasiId, setKonfirmasiId] = useState(null);
  const [pesanInfo, setPesanInfo] = useState("");

  /* RINGKASAN PESANAN */

  const ringkasan = {
    Baru: pesanan.filter(
      (item) => item.status === "Baru"
    ).length,

    Diproses: pesanan.filter(
      (item) => item.status === "Diproses"
    ).length,

    "Siap Diambil": pesanan.filter(
      (item) => item.status === "Siap Diambil"
    ).length,

    Selesai: pesanan.filter(
      (item) => item.status === "Selesai"
    ).length
  };

  /* FILTER DAN PENCARIAN */

  const pesananTampil = useMemo(() => {
    const kata = pencarian.trim().toLowerCase();

    return pesanan.filter((item) => {
      const cocokCari =
        item.id.toLowerCase().includes(kata) ||
        item.pelanggan.toLowerCase().includes(kata) ||
        item.items.some((menu) =>
          menu.nama.toLowerCase().includes(kata)
        );

      const cocokStatus =
        filter === "Semua" ||
        item.status === filter;

      return cocokCari && cocokStatus;
    });
  }, [pesanan, pencarian, filter]);

  const detail = pesanan.find(
    (item) => item.id === detailId
  );

  const konfirmasi = pesanan.find(
    (item) => item.id === konfirmasiId
  );

  /* =====================================
     PROSES PESANAN
  ===================================== */

  function mintaUbahStatus(id) {
    const ditemukan = pesanan.find(
      (item) => item.id === id
    );

    if (!ditemukan) return;

    if (ditemukan.pembayaran !== "Lunas") {
      setPesanInfo(
        "Pesanan belum lunas. Tunggu konfirmasi pembayaran otomatis."
      );
      return;
    }

    if (!langkahBerikutnya[ditemukan.status]) {
      return;
    }

    setKonfirmasiId(id);
  }

  function simpanStatus() {
    const item = pesanan.find(
      (pesananItem) => pesananItem.id === konfirmasiId
    );

    if (!item) {
      setKonfirmasiId(null);
      return;
    }

    const statusBaru = langkahBerikutnya[item.status];

    if (
      !statusBaru ||
      item.pembayaran !== "Lunas"
    ) {
      setKonfirmasiId(null);
      return;
    }

    setPesanan((sebelumnya) =>
      sebelumnya.map((pesananItem) =>
        pesananItem.id === item.id
          ? {
              ...pesananItem,
              status: statusBaru
            }
          : pesananItem
      )
    );

    setPesanInfo(
      `Pesanan #${item.id} berhasil diubah menjadi ${statusBaru}.`
    );

    setKonfirmasiId(null);
  }

  /* =====================================
     TAMPILAN HALAMAN
  ===================================== */

  return (
    <div className="po-page">

      {/* HEADER */}
      <div className="po-heading">
        <div>
          <h1>Pesanan Online</h1>
          <p>
            Pantau pesanan masuk dan proses pesanan
            pelanggan sampai selesai.
          </p>
        </div>

        <span className="po-heading-info">
          {ringkasan.Baru} pesanan baru
        </span>
      </div>

      {/* NOTIFIKASI */}
      {pesanInfo && (
        <div className="po-info" role="status">
          <CircleCheck size={16} />

          <span>{pesanInfo}</span>

          <button
            type="button"
            aria-label="Tutup pemberitahuan"
            onClick={() => setPesanInfo("")}
          >
            <X size={15} />
          </button>
        </div>
      )}

      {/* =====================================
          KARTU RINGKASAN STATUS
      ===================================== */}

      <div className="po-stats">
        {kartuStatus.map(
          ({ status, label, icon: Icon, warna }) => (
            <button
              type="button"
              key={status}
              className={`po-stat-card ${
                filter === status ? "dipilih" : ""
              }`}
              onClick={() =>
                setFilter(
                  filter === status ? "Semua" : status
                )
              }
              title={`Filter pesanan ${status}`}
            >
              <span className={`po-stat-icon ${warna}`}>
                <Icon size={19} />
              </span>

              <span className="po-stat-text">
                <small>{label}</small>
                <strong>{ringkasan[status]}</strong>
                <em>Lihat pesanan</em>
              </span>
            </button>
          )
        )}
      </div>

      {/* =====================================
          DAFTAR PESANAN
      ===================================== */}

      <section className="po-panel">

        <div className="po-panel-heading">
          <div>
            <h2>Daftar Pesanan Online</h2>
            <p>
              Data pesanan pelanggan yang sudah masuk.
            </p>
          </div>

          <span className="po-total">
            {pesananTampil.length} pesanan
          </span>
        </div>

        {/* PENCARIAN DAN FILTER */}
        <div className="po-toolbar">
          <label className="po-search">
            <Search size={17} />

            <input
              type="search"
              placeholder="Cari kode, pelanggan, atau menu..."
              value={pencarian}
              onChange={(event) =>
                setPencarian(event.target.value)
              }
            />
          </label>

          <select
            aria-label="Filter status pesanan"
            className="po-filter"
            value={filter}
            onChange={(event) =>
              setFilter(event.target.value)
            }
          >
            {daftarStatus.map((status) => (
              <option key={status} value={status}>
                {status === "Semua"
                  ? "Semua Status"
                  : status}
              </option>
            ))}
          </select>
        </div>

        {/* TABEL PESANAN */}
        <div className="po-table-wrap">
          <table className="po-table">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Waktu</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              {pesananTampil.map((item) => (
                <tr key={item.id}>

                  {/* KODE */}
                  <td className="po-code">
                    #{item.id}
                  </td>

                  {/* PELANGGAN */}
                  <td>
                    <div className="po-customer">
                      <strong>{item.pelanggan}</strong>

                      <small>
                        {jumlahItem(item)} item •{" "}
                        {item.items
                          .map((menu) => menu.nama)
                          .join(", ")}
                      </small>
                    </div>
                  </td>

                  {/* WAKTU */}
                  <td>{item.waktu}</td>

                  {/* TOTAL */}
                  <td className="po-money">
                    {rupiah(totalPesanan(item))}
                  </td>

                  {/* PEMBAYARAN */}
                  <td>
                    <span className="po-paid">
                      {item.pembayaran}
                    </span>

                    <small className="po-method">
                      {item.metode}
                    </small>
                  </td>

                  {/* STATUS */}
                  <td>
                    <BadgeStatus status={item.status} />
                  </td>

                  {/* AKSI */}
                  <td>
                    <div className="po-actions">

                      <button
                        type="button"
                        className="po-detail-btn"
                        onClick={() =>
                          setDetailId(item.id)
                        }
                      >
                        <Eye size={15} />
                        Detail
                      </button>

                      {langkahBerikutnya[item.status] && (
                        <button
                          type="button"
                          className="po-next-btn"
                          disabled={
                            item.pembayaran !== "Lunas"
                          }
                          onClick={() =>
                            mintaUbahStatus(item.id)
                          }
                          title={labelAksi[item.status]}
                          aria-label={`${
                            labelAksi[item.status]
                          } untuk ${item.id}`}
                        >
                          <ArrowRight size={15} />
                        </button>
                      )}

                    </div>
                  </td>

                </tr>
              ))}
            </tbody>
          </table>

          {/* TIDAK ADA HASIL */}
          {pesananTampil.length === 0 && (
            <div className="po-empty">
              <ClipboardList size={28} />
              <strong>
                Tidak ada pesanan ditemukan
              </strong>
              <p>
                Coba ubah pencarian atau filter status.
              </p>
            </div>
          )}
        </div>
      </section>

      {/* =====================================
          MODAL DETAIL PESANAN
      ===================================== */}

      {detail && (
        <div
          className="po-backdrop"
          onMouseDown={(event) => {
            if (
              event.target === event.currentTarget
            ) {
              setDetailId(null);
            }
          }}
        >
          <div
            className="po-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="po-detail-title"
          >

            {/* HEADER MODAL */}
            <div className="po-modal-header">
              <div>
                <h2 id="po-detail-title">
                  Detail Pesanan
                </h2>
                <p>#{detail.id}</p>
              </div>

              <button
                type="button"
                className="po-close"
                aria-label="Tutup detail"
                onClick={() => setDetailId(null)}
              >
                <X size={19} />
              </button>
            </div>

            {/* ISI DETAIL */}
            <div className="po-modal-body">

              <div className="po-detail-top">
                <BadgeStatus status={detail.status} />

                <span className="po-paid">
                  {detail.pembayaran}
                </span>
              </div>

              {/* INFORMASI PEMESAN */}
              <div className="po-detail-meta">

                <div>
                  <UserRound size={16} />
                  <span>Pelanggan</span>
                  <strong>
                    {detail.pelanggan}
                  </strong>
                </div>

                <div>
                  <CalendarDays size={16} />
                  <span>Waktu Pesanan</span>
                  <strong>
                    {detail.tanggal}, {detail.waktu}
                  </strong>
                </div>

                <div>
                  <Wallet size={16} />
                  <span>Pembayaran</span>
                  <strong>
                    {detail.metode} • {detail.pembayaran}
                  </strong>
                </div>

              </div>

              {/* RINCIAN MENU */}
              <h3>Rincian Menu</h3>

              <div className="po-item-list">
                {detail.items.map((item, index) => (
                  <div
                    className="po-order-item"
                    key={`${item.nama}-${index}`}
                  >
                    <div>
                      <strong>{item.nama}</strong>

                      <small>
                        {item.jumlah} ×{" "}
                        {rupiah(item.harga)}

                        {item.detail
                          ? ` • ${item.detail}`
                          : ""}
                      </small>
                    </div>

                    <strong>
                      {rupiah(
                        item.jumlah * item.harga
                      )}
                    </strong>
                  </div>
                ))}
              </div>

              {/* CATATAN PELANGGAN */}
              {detail.catatan && (
                <div className="po-note">
                  <strong>
                    Catatan Pelanggan
                  </strong>

                  <p>{detail.catatan}</p>
                </div>
              )}

              {/* TOTAL */}
              <div className="po-grand-total">
                <span>Total Pembayaran</span>

                <strong>
                  {rupiah(totalPesanan(detail))}
                </strong>
              </div>

              <p className="po-preview-note">
                Data pesanan dan pembayaran merupakan
                contoh frontend.
              </p>
            </div>

            {/* FOOTER MODAL */}
            <div className="po-modal-footer">

              <button
                type="button"
                className="po-cancel-btn"
                onClick={() => setDetailId(null)}
              >
                Tutup
              </button>

              {langkahBerikutnya[detail.status] && (
                <button
                  type="button"
                  className="po-primary-btn"
                  disabled={
                    detail.pembayaran !== "Lunas"
                  }
                  onClick={() => {
                    setDetailId(null);
                    mintaUbahStatus(detail.id);
                  }}
                >
                  {labelAksi[detail.status]}
                  <ArrowRight size={15} />
                </button>
              )}

            </div>
          </div>
        </div>
      )}

      {/* =====================================
          KONFIRMASI PERUBAHAN STATUS
      ===================================== */}

      {konfirmasi && (
        <div className="po-backdrop">
          <div
            className="po-confirm"
            role="dialog"
            aria-modal="true"
            aria-labelledby="po-confirm-title"
          >

            <div className="po-confirm-icon">
              <AlertCircle size={24} />
            </div>

            <h2 id="po-confirm-title">
              Ubah Status Pesanan?
            </h2>

            <p>
              Pesanan{" "}
              <strong>#{konfirmasi.id}</strong>{" "}
              akan diubah dari{" "}
              <strong>{konfirmasi.status}</strong>{" "}
              menjadi{" "}
              <strong>
                {langkahBerikutnya[
                  konfirmasi.status
                ]}
              </strong>.
            </p>

            <div className="po-confirm-actions">

              <button
                type="button"
                className="po-cancel-btn"
                onClick={() =>
                  setKonfirmasiId(null)
                }
              >
                Batal
              </button>

              <button
                type="button"
                className="po-primary-btn"
                onClick={simpanStatus}
              >
                Ya, Ubah Status
              </button>

            </div>
          </div>
        </div>
      )}

    </div>
  );
}
