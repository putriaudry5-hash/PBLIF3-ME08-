
import { useMemo, useState } from "react";
import {
  Plus,
  Minus,
  Search,
  ReceiptText,
  CircleCheck,
  Clock3,
  Pencil,
  Trash2,
  Eye,
  X,
  Save,
  ShoppingBasket,
  AlertCircle,
  UtensilsCrossed
} from "lucide-react";

import "../../styles/Tenant/PesananOfflineTenant.css";

// DATA MENU CONTOH
// Nanti diganti dengan data Kelola Menu dari API.
const katalogMenu = [
  {
    id: 1,
    nama: "Ayam Geprek",
    kategori: "Satuan",
    harga: 18000,
    tersedia: true,
    gambar:
      "https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=500&auto=format&fit=crop&q=80"
  },
  {
    id: 2,
    nama: "Nasi Goreng",
    kategori: "Satuan",
    harga: 15000,
    tersedia: true,
    gambar:
      "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=500&auto=format&fit=crop&q=80"
  },
  {
    id: 3,
    nama: "Mie Goreng",
    kategori: "Satuan",
    harga: 13000,
    tersedia: true,
    gambar:
      "https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500&auto=format&fit=crop&q=80"
  },
  {
    id: 4,
    nama: "Ayam Bakar",
    kategori: "Satuan",
    harga: 20000,
    tersedia: true,
    gambar:
      "https://images.unsplash.com/photo-1532550907401-a500c9a57435?w=500&auto=format&fit=crop&q=80"
  },
  {
    id: 5,
    nama: "Paket Prasmanan",
    kategori: "Prasmanan",
    pilihanHarga: [2000, 3000, 4000, 5000],
    tersedia: true,
    gambar:
      "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop&q=80"
  },
  {
    id: 6,
    nama: "Telur Balado",
    kategori: "Satuan",
    harga: 5000,
    tersedia: true,
    gambar:
      "https://images.unsplash.com/photo-1525351484163-7529414344d8?w=500&auto=format&fit=crop&q=80"
  }
];

const dataAwal = [
  {
    id: "OFF001",
    pelanggan: "Pelanggan Offline",
    waktu: "10:35",
    tanggal: "20 Okt 2026",
    status: "Menunggu Kasir",
    catatan: "",
    items: [
      { menuId: 2, nama: "Nasi Goreng", harga: 15000, jumlah: 1 },
      { menuId: 6, nama: "Telur Balado", harga: 5000, jumlah: 1 }
    ]
  },
  {
    id: "OFF002",
    pelanggan: "Pelanggan Offline",
    waktu: "10:12",
    tanggal: "20 Okt 2026",
    status: "Lunas",
    catatan: "Makan di tempat",
    items: [
      { menuId: 5, nama: "Paket Prasmanan", harga: 5000, jumlah: 1 },
      { menuId: 1, nama: "Ayam Geprek", harga: 18000, jumlah: 1 }
    ]
  },
  {
    id: "OFF003",
    pelanggan: "Pelanggan Offline",
    waktu: "09:45",
    tanggal: "20 Okt 2026",
    status: "Menunggu Kasir",
    catatan: "",
    items: [
      { menuId: 3, nama: "Mie Goreng", harga: 13000, jumlah: 2 }
    ]
  }
];

const rupiah = (angka) =>
  `Rp ${Number(angka).toLocaleString("id-ID")}`;

const hitungTotal = (items) =>
  items.reduce(
    (total, item) => total + item.harga * item.jumlah,
    0
  );

const hitungJumlah = (items) =>
  items.reduce((total, item) => total + item.jumlah, 0);

const formAwal = () => ({
  pelanggan: "Pelanggan Offline",
  catatan: "",
  items: []
});

function tanggalSekarang() {
  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric"
  }).format(new Date());
}

function waktuSekarang() {
  return new Intl.DateTimeFormat("id-ID", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: false
  }).format(new Date());
}

// FOTO MENU DENGAN TAMPILAN CADANGAN
function FotoMenu({ menu }) {
  const [gagal, setGagal] = useState(false);

  if (gagal || !menu.gambar) {
    return (
      <div className="of-menu-photo of-photo-fallback">
        <UtensilsCrossed size={27} />
      </div>
    );
  }

  return (
    <img
      className="of-menu-photo"
      src={menu.gambar}
      alt={menu.nama}
      loading="lazy"
      onError={() => setGagal(true)}
    />
  );
}

export default function PesananOfflineTenant() {
  const [pesanan, setPesanan] = useState(dataAwal);
  const [pencarian, setPencarian] = useState("");
  const [filter, setFilter] = useState("Semua");

  const [modalOpen, setModalOpen] = useState(false);
  const [editId, setEditId] = useState(null);
  const [form, setForm] = useState(formAwal);

  const [cariMenu, setCariMenu] = useState("");
  const [kategori, setKategori] = useState("Semua");

  const [detailId, setDetailId] = useState(null);
  const [hapusId, setHapusId] = useState(null);
  const [error, setError] = useState("");
  const [notifikasi, setNotifikasi] = useState("");

  // RINGKASAN
  const jumlahMenunggu = pesanan.filter(
    (item) => item.status === "Menunggu Kasir"
  ).length;

  const jumlahLunas = pesanan.filter(
    (item) => item.status === "Lunas"
  ).length;

  const totalLunas = pesanan
    .filter((item) => item.status === "Lunas")
    .reduce((total, item) => total + hitungTotal(item.items), 0);

  // FILTER PESANAN
  const pesananTampil = useMemo(() => {
    const kata = pencarian.trim().toLowerCase();

    return pesanan.filter((item) => {
      const cocokCari =
        item.id.toLowerCase().includes(kata) ||
        item.pelanggan.toLowerCase().includes(kata) ||
        item.items.some((makanan) =>
          makanan.nama.toLowerCase().includes(kata)
        );

      return (
        cocokCari &&
        (filter === "Semua" || item.status === filter)
      );
    });
  }, [pesanan, pencarian, filter]);

  // FILTER KATALOG MAKANAN
  const menuTampil = useMemo(() => {
    const kata = cariMenu.toLowerCase().trim();

    return katalogMenu.filter(
      (menu) =>
        menu.nama.toLowerCase().includes(kata) &&
        (kategori === "Semua" || menu.kategori === kategori)
    );
  }, [cariMenu, kategori]);

  const detail = pesanan.find((p) => p.id === detailId);
  const dataHapus = pesanan.find((p) => p.id === hapusId);

  const totalForm = hitungTotal(form.items);

  function bukaTambah() {
    setForm(formAwal());
    setEditId(null);
    setCariMenu("");
    setKategori("Semua");
    setError("");
    setModalOpen(true);
  }

  function bukaEdit(item) {
    if (item.status !== "Menunggu Kasir") return;

    setEditId(item.id);
    setForm({
      pelanggan: item.pelanggan,
      catatan: item.catatan,
      items: item.items.map((menu) => ({ ...menu }))
    });
    setCariMenu("");
    setKategori("Semua");
    setError("");
    setModalOpen(true);
  }

  function tutupModal() {
    setModalOpen(false);
    setEditId(null);
    setError("");
  }

  // MENAMBAHKAN MENU KE PESANAN
  function tambahKePesanan(menu, hargaPilihan) {
    if (!menu.tersedia) return;

    const harga =
      menu.kategori === "Prasmanan"
        ? Number(hargaPilihan)
        : Number(menu.harga);

    if (!Number.isFinite(harga) || harga <= 0) return;

    setForm((sebelumnya) => {
      const sudahAda = sebelumnya.items.findIndex(
        (item) =>
          item.menuId === menu.id && item.harga === harga
      );

      if (sudahAda >= 0) {
        return {
          ...sebelumnya,
          items: sebelumnya.items.map((item, index) =>
            index === sudahAda
              ? { ...item, jumlah: item.jumlah + 1 }
              : item
          )
        };
      }

      return {
        ...sebelumnya,
        items: [
          ...sebelumnya.items,
          {
            menuId: menu.id,
            nama: menu.nama,
            harga,
            jumlah: 1
          }
        ]
      };
    });

    setError("");
  }

  function ubahJumlah(index, perubahan) {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      items: sebelumnya.items.map((item, posisi) =>
        posisi === index
          ? {
              ...item,
              jumlah: Math.max(1, item.jumlah + perubahan)
            }
          : item
      )
    }));
  }

  function hapusDariPesanan(index) {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      items: sebelumnya.items.filter(
        (_, posisi) => posisi !== index
      )
    }));
  }

  // SIMPAN PESANAN
  function simpanPesanan(event) {
    event.preventDefault();

    if (form.items.length === 0) {
      setError("Pilih minimal satu makanan terlebih dahulu.");
      return;
    }

    const pelanggan =
      form.pelanggan.trim() || "Pelanggan Offline";

    if (editId !== null) {
      const lama = pesanan.find((p) => p.id === editId);

      if (!lama || lama.status !== "Menunggu Kasir") {
        setError("Pesanan sudah tidak dapat diubah.");
        return;
      }

      setPesanan((sebelumnya) =>
        sebelumnya.map((item) =>
          item.id === editId
            ? {
                ...item,
                pelanggan,
                catatan: form.catatan.trim(),
                items: form.items.map((m) => ({ ...m }))
              }
            : item
        )
      );

      setNotifikasi(
        `Pesanan #${editId} berhasil diperbarui.`
      );
    } else {
      const nomorTerakhir = Math.max(
        0,
        ...pesanan.map(
          (p) => Number(p.id.replace("OFF", "")) || 0
        )
      );

      const id = `OFF${String(
        nomorTerakhir + 1
      ).padStart(3, "0")}`;

      const pesananBaru = {
        id,
        pelanggan,
        catatan: form.catatan.trim(),
        tanggal: tanggalSekarang(),
        waktu: waktuSekarang(),
        status: "Menunggu Kasir",
        items: form.items.map((m) => ({ ...m }))
      };

      setPesanan((sebelumnya) => [
        pesananBaru,
        ...sebelumnya
      ]);

      setNotifikasi(
        `Pesanan #${id} disimpan. Menunggu pembayaran di Kasir Utama.`
      );
    }

    tutupModal();
  }

  function konfirmasiHapus() {
    if (
      !dataHapus ||
      dataHapus.status !== "Menunggu Kasir"
    ) {
      setHapusId(null);
      return;
    }

    setPesanan((sebelumnya) =>
      sebelumnya.filter((item) => item.id !== hapusId)
    );

    setNotifikasi(
      `Pesanan #${hapusId} berhasil dihapus.`
    );
    setHapusId(null);
  }

  return (
    <div className="of-page">
      {/* HEADER */}
      <div className="of-heading">
        <div>
          <h1>Pesanan Offline</h1>
          <p>
            Pilih makanan dari katalog dan catat pembelian pelanggan.
          </p>
        </div>

        <button
          type="button"
          className="of-primary"
          onClick={bukaTambah}
        >
          <Plus size={16} />
          Input Pesanan
        </button>
      </div>

      {/* NOTIFIKASI */}
      {notifikasi && (
        <div className="of-notice" role="status">
          <CircleCheck size={16} />
          <span>{notifikasi}</span>
          <button
            type="button"
            aria-label="Tutup pemberitahuan"
            onClick={() => setNotifikasi("")}
          >
            <X size={15} />
          </button>
        </div>
      )}

      {/* STATISTIK */}
      <div className="of-stats">
        <div className="of-stat">
          <div className="of-stat-icon yellow">
            <ReceiptText size={19} />
          </div>
          <div>
            <small>Total Pesanan</small>
            <strong>{pesanan.length}</strong>
            <span>Pesanan tercatat</span>
          </div>
        </div>

        <div className="of-stat">
          <div className="of-stat-icon orange">
            <Clock3 size={19} />
          </div>
          <div>
            <small>Menunggu Kasir</small>
            <strong>{jumlahMenunggu}</strong>
            <span>Belum dibayar</span>
          </div>
        </div>

        <div className="of-stat">
          <div className="of-stat-icon green">
            <CircleCheck size={19} />
          </div>
          <div>
            <small>Sudah Lunas</small>
            <strong>{jumlahLunas}</strong>
            <span>Tercatat oleh kasir</span>
          </div>
        </div>

        <div className="of-stat">
          <div className="of-stat-icon maroon">
            <ReceiptText size={19} />
          </div>
          <div>
            <small>Total Lunas</small>
            <strong className="of-stat-money">
              {rupiah(totalLunas)}
            </strong>
            <span>Pembayaran selesai</span>
          </div>
        </div>
      </div>

      {/* DAFTAR PESANAN */}
      <section className="of-panel">
        <div className="of-panel-header">
          <div>
            <h2>Daftar Pesanan Offline</h2>
            <p>Pesanan yang diinput oleh tenant.</p>
          </div>

          <span className="of-total-label">
            {pesananTampil.length} pesanan
          </span>
        </div>

        <div className="of-toolbar">
          <label className="of-search">
            <Search size={16} />
            <input
              type="search"
              placeholder="Cari kode, pelanggan, atau makanan..."
              value={pencarian}
              onChange={(e) => setPencarian(e.target.value)}
            />
          </label>

          <select
            aria-label="Filter pembayaran"
            value={filter}
            onChange={(e) => setFilter(e.target.value)}
          >
            <option value="Semua">Semua Status</option>
            <option value="Menunggu Kasir">
              Menunggu Kasir
            </option>
            <option value="Lunas">Lunas</option>
          </select>
        </div>

        <div className="of-table-wrap">
          <table className="of-table">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Waktu</th>
                <th>Pelanggan</th>
                <th>Makanan</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              {pesananTampil.map((item) => (
                <tr key={item.id}>
                  <td className="of-code">#{item.id}</td>

                  <td>
                    <strong className="of-cell-strong">
                      {item.waktu}
                    </strong>
                    <small className="of-cell-small">
                      {item.tanggal}
                    </small>
                  </td>

                  <td>{item.pelanggan}</td>

                  <td>
                    <div className="of-food-cell">
                      <strong>
                        {item.items.map((m) => m.nama).join(", ")}
                      </strong>
                      <small>
                        {hitungJumlah(item.items)} item
                      </small>
                    </div>
                  </td>

                  <td className="of-total-price">
                    {rupiah(hitungTotal(item.items))}
                  </td>

                  <td>
                    <span
                      className={`of-status ${
                        item.status === "Lunas"
                          ? "paid"
                          : "pending"
                      }`}
                    >
                      {item.status}
                    </span>
                  </td>

                  <td>
                    <div className="of-actions">
                      <button
                        type="button"
                        title="Lihat Detail"
                        aria-label={`Detail ${item.id}`}
                        onClick={() => setDetailId(item.id)}
                      >
                        <Eye size={15} />
                      </button>

                      {item.status === "Menunggu Kasir" && (
                        <>
                          <button
                            type="button"
                            title="Edit"
                            aria-label={`Edit ${item.id}`}
                            onClick={() => bukaEdit(item)}
                          >
                            <Pencil size={15} />
                          </button>

                          <button
                            type="button"
                            className="danger"
                            title="Hapus"
                            aria-label={`Hapus ${item.id}`}
                            onClick={() => setHapusId(item.id)}
                          >
                            <Trash2 size={15} />
                          </button>
                        </>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {pesananTampil.length === 0 && (
            <div className="of-empty">
              <ReceiptText size={27} />
              <strong>Pesanan tidak ditemukan</strong>
              <p>Coba ubah pencarian atau filter.</p>
            </div>
          )}
        </div>
      </section>

      {/* MODAL INPUT DAN EDIT */}
      {modalOpen && (
        <div className="of-backdrop">
          <div
            className="of-modal of-catalog-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="of-form-title"
          >
            <div className="of-modal-header">
              <div>
                <h2 id="of-form-title">
                  {editId
                    ? "Edit Pesanan Offline"
                    : "Input Pesanan Offline"}
                </h2>
                <p>
                  Pilih menu, tentukan porsi, lalu simpan pesanan.
                </p>
              </div>

              <button
                type="button"
                className="of-close"
                aria-label="Tutup form"
                onClick={tutupModal}
              >
                <X size={18} />
              </button>
            </div>

            <form onSubmit={simpanPesanan}>
              <div className="of-modal-body">
                {error && (
                  <div className="of-error" role="alert">
                    <AlertCircle size={16} />
                    {error}
                  </div>
                )}

                <label className="of-field">
                  <span>Nama Pelanggan</span>
                  <input
                    type="text"
                    maxLength={100}
                    value={form.pelanggan}
                    onChange={(e) =>
                      setForm((prev) => ({
                        ...prev,
                        pelanggan: e.target.value
                      }))
                    }
                  />
                </label>

                {/* PILIH MAKANAN */}
                <div className="of-catalog-heading">
                  <div>
                    <h3>Pilih Makanan</h3>
                    <p>
                      Klik Tambah atau pilih harga untuk prasmanan.
                    </p>
                  </div>
                </div>

                <div className="of-catalog-toolbar">
                  <label className="of-catalog-search">
                    <Search size={15} />
                    <input
                      type="search"
                      placeholder="Cari makanan..."
                      value={cariMenu}
                      onChange={(e) => setCariMenu(e.target.value)}
                    />
                  </label>

                  <select
                    aria-label="Kategori makanan"
                    value={kategori}
                    onChange={(e) => setKategori(e.target.value)}
                  >
                    <option value="Semua">Semua</option>
                    <option value="Satuan">Satuan</option>
                    <option value="Prasmanan">Prasmanan</option>
                  </select>
                </div>

                <div className="of-menu-grid">
                  {menuTampil.map((menu) => (
                    <div className="of-menu-card" key={menu.id}>
                      <FotoMenu menu={menu} />

                      <div className="of-menu-card-content">
                        <span className="of-menu-category">
                          {menu.kategori}
                        </span>

                        <strong>{menu.nama}</strong>

                        {menu.kategori === "Satuan" ? (
                          <>
                            <span className="of-menu-price">
                              {rupiah(menu.harga)}
                            </span>

                            <button
                              type="button"
                              className="of-menu-add"
                              onClick={() => tambahKePesanan(menu)}
                            >
                              <Plus size={14} />
                              Tambah
                            </button>
                          </>
                        ) : (
                          <>
                            <small className="of-menu-price-hint">
                              Pilih harga prasmanan
                            </small>

                            <div className="of-menu-price-options">
                              {menu.pilihanHarga.map((harga) => (
                                <button
                                  key={harga}
                                  type="button"
                                  onClick={() =>
                                    tambahKePesanan(menu, harga)
                                  }
                                >
                                  {harga.toLocaleString("id-ID")}
                                </button>
                              ))}
                            </div>
                          </>
                        )}
                      </div>
                    </div>
                  ))}
                </div>

                {menuTampil.length === 0 && (
                  <p className="of-catalog-empty">
                    Makanan tidak ditemukan.
                  </p>
                )}

                {/* KERANJANG */}
                <div className="of-cart">
                  <div className="of-cart-heading">
                    <h3>
                      <ShoppingBasket size={17} />
                      Ringkasan Pesanan
                    </h3>
                    <span>
                      {hitungJumlah(form.items)} item
                    </span>
                  </div>

                  {form.items.length === 0 ? (
                    <div className="of-cart-empty">
                      Belum ada makanan dipilih.
                    </div>
                  ) : (
                    <div className="of-cart-items">
                      {form.items.map((item, index) => (
                        <div className="of-cart-item" key={`${item.menuId}-${item.harga}`}>
                          <div className="of-cart-name">
                            <strong>{item.nama}</strong>
                            <small>
                              {rupiah(item.harga)} / porsi
                            </small>
                          </div>

                          <div className="of-quantity">
                            <button
                              type="button"
                              aria-label={`Kurangi ${item.nama}`}
                              onClick={() => ubahJumlah(index, -1)}
                            >
                              <Minus size={13} />
                            </button>
                            <span>{item.jumlah}</span>
                            <button
                              type="button"
                              aria-label={`Tambah ${item.nama}`}
                              onClick={() => ubahJumlah(index, 1)}
                            >
                              <Plus size={13} />
                            </button>
                          </div>

                          <strong className="of-cart-subtotal">
                            {rupiah(item.harga * item.jumlah)}
                          </strong>

                          <button
                            type="button"
                            className="of-cart-remove"
                            title="Hapus makanan"
                            aria-label={`Hapus ${item.nama}`}
                            onClick={() => hapusDariPesanan(index)}
                          >
                            <Trash2 size={15} />
                          </button>
                        </div>
                      ))}
                    </div>
                  )}
                </div>

                <label className="of-field">
                  <span>Catatan (Opsional)</span>
                  <textarea
                    rows={2}
                    value={form.catatan}
                    placeholder="Contoh: Makan di tempat"
                    onChange={(e) =>
                      setForm((prev) => ({
                        ...prev,
                        catatan: e.target.value
                      }))
                    }
                  />
                </label>

                <div className="of-form-total">
                  <span>Total Pesanan</span>
                  <strong>{rupiah(totalForm)}</strong>
                </div>

                <p className="of-form-note">
                  Tenant mencatat makanan dan harga. Pembayaran
                  tunai tetap diproses oleh Kasir Utama.
                </p>
              </div>

              <div className="of-modal-footer">
                <button
                  type="button"
                  className="of-cancel"
                  onClick={tutupModal}
                >
                  Batal
                </button>

                <button
                  type="submit"
                  className="of-primary"
                >
                  <Save size={15} />
                  {editId
                    ? "Simpan Perubahan"
                    : "Simpan Pesanan"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* DETAIL */}
      {detail && (
        <div className="of-backdrop">
          <div
            className="of-modal of-detail-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="of-detail-title"
          >
            <div className="of-modal-header">
              <div>
                <h2 id="of-detail-title">Detail Pesanan</h2>
                <p>#{detail.id}</p>
              </div>
              <button
                type="button"
                className="of-close"
                onClick={() => setDetailId(null)}
                aria-label="Tutup detail"
              >
                <X size={18} />
              </button>
            </div>

            <div className="of-modal-body">
              <div className="of-detail-info">
                <div>
                  <span>Pelanggan</span>
                  <strong>{detail.pelanggan}</strong>
                </div>
                <div>
                  <span>Waktu</span>
                  <strong>
                    {detail.tanggal}, {detail.waktu}
                  </strong>
                </div>
                <div>
                  <span>Status Pembayaran</span>
                  <span
                    className={`of-status ${
                      detail.status === "Lunas"
                        ? "paid"
                        : "pending"
                    }`}
                  >
                    {detail.status}
                  </span>
                </div>
              </div>

              <h3 className="of-detail-title">
                Rincian Makanan
              </h3>

              {detail.items.map((item, index) => (
                <div className="of-detail-item" key={index}>
                  <div>
                    <strong>{item.nama}</strong>
                    <small>
                      {item.jumlah} × {rupiah(item.harga)}
                    </small>
                  </div>
                  <strong>
                    {rupiah(item.harga * item.jumlah)}
                  </strong>
                </div>
              ))}

              {detail.catatan && (
                <p className="of-detail-note">
                  Catatan: {detail.catatan}
                </p>
              )}

              <div className="of-form-total">
                <span>Total Tagihan</span>
                <strong>
                  {rupiah(hitungTotal(detail.items))}
                </strong>
              </div>

              <p className="of-form-note">
                Pembayaran dicatat oleh Kasir Utama.
              </p>
            </div>

            <div className="of-modal-footer">
              <button
                type="button"
                className="of-primary"
                onClick={() => setDetailId(null)}
              >
                Tutup
              </button>
            </div>
          </div>
        </div>
      )}

      {/* HAPUS */}
      {dataHapus && (
        <div className="of-backdrop">
          <div
            className="of-confirm"
            role="dialog"
            aria-modal="true"
            aria-labelledby="of-confirm-title"
          >
            <div className="of-confirm-icon">
              <Trash2 size={23} />
            </div>
            <h2 id="of-confirm-title">
              Hapus Pesanan?
            </h2>
            <p>
              Pesanan #{dataHapus.id} akan dihapus.
              Pesanan lunas tidak dapat dihapus.
            </p>

            <div className="of-confirm-actions">
              <button
                type="button"
                className="of-cancel"
                onClick={() => setHapusId(null)}
              >
                Batal
              </button>

              <button
                type="button"
                className="of-delete"
                onClick={konfirmasiHapus}
              >
                Hapus Pesanan
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
