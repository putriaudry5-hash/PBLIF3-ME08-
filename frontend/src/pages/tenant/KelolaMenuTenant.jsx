
import { useMemo, useState } from "react";
import {
  Plus,
  Search,
  UtensilsCrossed,
  CircleCheck,
  CircleX,
  Pencil,
  Trash2,
  X,
  Save,
  AlertCircle
} from "lucide-react";

import "../../styles/Tenant/KelolaMenuTenant.css";

// =====================================
// DATA CONTOH MENU TENANT
// =====================================

const dataAwal = [
  {
    id: 1,
    nama: "Ayam Geprek",
    kategori: "Satuan",
    harga: 18000,
    pilihanHarga: [],
    stok: 20,
    tersedia: true,
    deskripsi: "Ayam geprek, nasi, dan sambal."
  },
  {
    id: 2,
    nama: "Nasi Goreng",
    kategori: "Satuan",
    harga: 15000,
    pilihanHarga: [],
    stok: 15,
    tersedia: true,
    deskripsi: "Nasi goreng spesial."
  },
  {
    id: 3,
    nama: "Mie Goreng",
    kategori: "Satuan",
    harga: 13000,
    pilihanHarga: [],
    stok: 12,
    tersedia: true,
    deskripsi: "Mie goreng dengan sayuran."
  },
  {
    id: 4,
    nama: "Ayam Bakar",
    kategori: "Satuan",
    harga: 20000,
    pilihanHarga: [],
    stok: 0,
    tersedia: false,
    deskripsi: "Ayam bakar dengan nasi."
  },
  {
    id: 5,
    nama: "Paket Prasmanan",
    kategori: "Prasmanan",
    harga: null,
    pilihanHarga: [2000, 3000, 4000, 5000],
    stok: 30,
    tersedia: true,
    deskripsi: "Pilihan porsi dan lauk prasmanan."
  },
  {
    id: 6,
    nama: "Telur Balado",
    kategori: "Satuan",
    harga: 5000,
    pilihanHarga: [],
    stok: 0,
    tersedia: false,
    deskripsi: "Telur balado per potong."
  }
];

const formKosong = {
  nama: "",
  kategori: "Satuan",
  harga: "",
  pilihanHarga: ["2000", "3000", "4000", "5000"],
  stok: "",
  tersedia: true,
  deskripsi: ""
};

function rupiah(angka) {
  return `Rp ${Number(angka).toLocaleString("id-ID")}`;
}

// =====================================
// KOMPONEN KELOLA MENU
// =====================================

export default function KelolaMenuTenant() {
  const [menus, setMenus] = useState(dataAwal);

  const [pencarian, setPencarian] = useState("");
  const [filter, setFilter] = useState("Semua");

  const [modalOpen, setModalOpen] = useState(false);
  const [editId, setEditId] = useState(null);

  const [form, setForm] = useState({
    ...formKosong,
    pilihanHarga: [...formKosong.pilihanHarga]
  });

  const [error, setError] = useState("");
  const [hapusId, setHapusId] = useState(null);
  const [pesan, setPesan] = useState("");

  // =====================================
  // RINGKASAN MENU
  // =====================================

  const totalMenu = menus.length;

  const totalTersedia = menus.filter(
    (menu) => menu.tersedia && menu.stok > 0
  ).length;

  const totalHabis = totalMenu - totalTersedia;

  // =====================================
  // PENCARIAN DAN FILTER
  // =====================================

  const menuDitampilkan = useMemo(() => {
    return menus.filter((menu) => {
      const cocokPencarian = menu.nama
        .toLowerCase()
        .includes(pencarian.toLowerCase().trim());

      const cocokFilter =
        filter === "Semua" ||
        filter === menu.kategori ||
        (filter === "Tersedia" &&
          menu.tersedia &&
          menu.stok > 0) ||
        (filter === "Habis" &&
          (!menu.tersedia || menu.stok === 0));

      return cocokPencarian && cocokFilter;
    });
  }, [menus, pencarian, filter]);

  // =====================================
  // TAMBAH MENU
  // =====================================

  function bukaTambah() {
    setEditId(null);

    setForm({
      ...formKosong,
      pilihanHarga: [...formKosong.pilihanHarga]
    });

    setError("");
    setModalOpen(true);
  }

  // =====================================
  // EDIT MENU
  // =====================================

  function bukaEdit(menu) {
    setEditId(menu.id);

    setForm({
      nama: menu.nama,
      kategori: menu.kategori,
      harga:
        menu.harga === null
          ? ""
          : String(menu.harga),
      pilihanHarga:
        menu.pilihanHarga.length > 0
          ? menu.pilihanHarga.map(String)
          : ["2000", "3000", "4000", "5000"],
      stok: String(menu.stok),
      tersedia: menu.tersedia,
      deskripsi: menu.deskripsi
    });

    setError("");
    setModalOpen(true);
  }

  function tutupModal() {
    setModalOpen(false);
    setEditId(null);
    setError("");
  }

  function ubahForm(nama, nilai) {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      [nama]: nilai
    }));

    setError("");
  }

  // =====================================
  // PILIHAN HARGA PRASMANAN
  // =====================================

  function tambahPilihanHarga() {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      pilihanHarga: [
        ...sebelumnya.pilihanHarga,
        ""
      ]
    }));

    setError("");
  }

  function ubahPilihanHarga(index, nilai) {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      pilihanHarga: sebelumnya.pilihanHarga.map(
        (harga, i) => (i === index ? nilai : harga)
      )
    }));

    setError("");
  }

  function hapusPilihanHarga(index) {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      pilihanHarga: sebelumnya.pilihanHarga.filter(
        (_, i) => i !== index
      )
    }));

    setError("");
  }

  // =====================================
  // SIMPAN MENU
  // =====================================

  function simpanMenu(event) {
    event.preventDefault();

    const nama = form.nama.trim();
    const stok = Number(form.stok);
    const isPrasmanan = form.kategori === "Prasmanan";

    if (!nama) {
      setError("Nama menu wajib diisi.");
      return;
    }

    if (
      form.stok === "" ||
      !Number.isInteger(stok) ||
      stok < 0
    ) {
      setError(
        "Stok harus berupa bilangan bulat minimal 0."
      );
      return;
    }

    let harga = null;
    let pilihanHarga = [];

    if (isPrasmanan) {
      const hargaInput = form.pilihanHarga.map(Number);

      const adaHargaKosong = form.pilihanHarga.some(
        (item) => String(item).trim() === ""
      );

      if (
        hargaInput.length === 0 ||
        adaHargaKosong
      ) {
        setError(
          "Prasmanan wajib memiliki minimal satu pilihan harga."
        );
        return;
      }

      const semuaValid = hargaInput.every(
        (nilai) =>
          Number.isInteger(nilai) &&
          nilai > 0
      );

      if (!semuaValid) {
        setError(
          "Pilihan harga harus berupa angka lebih dari 0."
        );
        return;
      }

      const hargaUnik = new Set(hargaInput);

      if (hargaUnik.size !== hargaInput.length) {
        setError(
          "Pilihan harga tidak boleh sama."
        );
        return;
      }

      pilihanHarga = [...hargaInput].sort(
        (a, b) => a - b
      );
    } else {
      harga = Number(form.harga);

      if (
        form.harga === "" ||
        !Number.isInteger(harga) ||
        harga <= 0
      ) {
        setError(
          "Harga menu satuan harus lebih dari 0."
        );
        return;
      }
    }

    const menuBaru = {
      id: editId ?? Date.now(),
      nama,
      kategori: form.kategori,
      harga,
      pilihanHarga,
      stok,
      tersedia: stok > 0 && form.tersedia,
      deskripsi: form.deskripsi.trim()
    };

    if (editId !== null) {
      setMenus((sebelumnya) =>
        sebelumnya.map((menu) =>
          menu.id === editId
            ? menuBaru
            : menu
        )
      );

      setPesan(
        `Menu "${nama}" berhasil diperbarui.`
      );
    } else {
      setMenus((sebelumnya) => [
        ...sebelumnya,
        menuBaru
      ]);

      setPesan(
        `Menu "${nama}" berhasil ditambahkan.`
      );
    }

    tutupModal();
  }

  // =====================================
  // STATUS TERSEDIA / NONAKTIF
  // =====================================

  function ubahKetersediaan(id) {
    const menu = menus.find(
      (item) => item.id === id
    );

    if (!menu) return;

    if (menu.stok === 0) {
      setPesan(
        `Stok "${menu.nama}" habis. Edit stok terlebih dahulu.`
      );
      return;
    }

    setMenus((sebelumnya) =>
      sebelumnya.map((item) =>
        item.id === id
          ? {
              ...item,
              tersedia: !item.tersedia
            }
          : item
      )
    );

    setPesan(
      `Status "${menu.nama}" berhasil diubah.`
    );
  }

  // =====================================
  // HAPUS MENU
  // =====================================

  function konfirmasiHapus() {
    const menu = menus.find(
      (item) => item.id === hapusId
    );

    if (!menu) {
      setHapusId(null);
      return;
    }

    setMenus((sebelumnya) =>
      sebelumnya.filter(
        (item) => item.id !== hapusId
      )
    );

    setPesan(
      `Menu "${menu.nama}" berhasil dihapus.`
    );

    setHapusId(null);
  }

  // =====================================
  // TAMPILAN HALAMAN
  // =====================================

  return (
    <div className="km-page">

      {/* HEADER */}
      <div className="km-heading">
        <div>
          <h1>Kelola Menu</h1>
          <p>
            Kelola menu makanan, harga, stok, dan
            ketersediaan di tenant Anda.
          </p>
        </div>

        <button
          type="button"
          className="km-primary-btn"
          onClick={bukaTambah}
        >
          <Plus size={17} />
          Tambah Menu
        </button>
      </div>

      {/* NOTIFIKASI */}
      {pesan && (
        <div className="km-message" role="status">
          <CircleCheck size={17} />
          <span>{pesan}</span>

          <button
            type="button"
            aria-label="Tutup pesan"
            onClick={() => setPesan("")}
          >
            <X size={16} />
          </button>
        </div>
      )}

      {/* STATISTIK */}
      <div className="km-stats">

        <div className="km-stat-card">
          <div className="km-stat-icon kuning">
            <UtensilsCrossed size={21} />
          </div>

          <div>
            <span>Total Menu</span>
            <strong>{totalMenu}</strong>
            <small>Menu terdaftar</small>
          </div>
        </div>

        <div className="km-stat-card">
          <div className="km-stat-icon hijau">
            <CircleCheck size={21} />
          </div>

          <div>
            <span>Menu Tersedia</span>
            <strong>{totalTersedia}</strong>
            <small>Dapat dipesan pelanggan</small>
          </div>
        </div>

        <div className="km-stat-card">
          <div className="km-stat-icon merah">
            <CircleX size={21} />
          </div>

          <div>
            <span>Menu Habis / Nonaktif</span>
            <strong>{totalHabis}</strong>
            <small>Belum dapat dipesan</small>
          </div>
        </div>

      </div>

      {/* DAFTAR MENU */}
      <section className="km-panel">

        <div className="km-panel-heading">
          <div>
            <h2>Daftar Menu</h2>
            <p>
              Kelola menu satuan dan pilihan harga prasmanan.
            </p>
          </div>

          <span className="km-total-label">
            {menuDitampilkan.length} menu
          </span>
        </div>

        {/* PENCARIAN DAN FILTER */}
        <div className="km-toolbar">

          <div className="km-search">
            <Search size={17} />

            <input
              type="text"
              placeholder="Cari nama menu..."
              value={pencarian}
              onChange={(event) =>
                setPencarian(event.target.value)
              }
            />
          </div>

          <select
            className="km-filter"
            value={filter}
            aria-label="Filter menu"
            onChange={(event) =>
              setFilter(event.target.value)
            }
          >
            <option value="Semua">Semua Menu</option>
            <option value="Satuan">Menu Satuan</option>
            <option value="Prasmanan">Prasmanan</option>
            <option value="Tersedia">Tersedia</option>
            <option value="Habis">Habis / Nonaktif</option>
          </select>

        </div>

        {/* TABEL MENU */}
        <div className="km-table-wrap">
          <table className="km-table">

            <thead>
              <tr>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              {menuDitampilkan.map((menu) => {
                const aktif =
                  menu.tersedia && menu.stok > 0;

                return (
                  <tr key={menu.id}>

                    {/* NAMA */}
                    <td>
                      <div className="km-menu-name">

                        <span className="km-menu-icon">
                          <UtensilsCrossed size={17} />
                        </span>

                        <div>
                          <strong>{menu.nama}</strong>
                          <small>
                            {menu.deskripsi ||
                              "Tanpa deskripsi"}
                          </small>
                        </div>

                      </div>
                    </td>

                    {/* KATEGORI */}
                    <td>
                      <span
                        className={`km-category ${
                          menu.kategori === "Prasmanan"
                            ? "prasmanan"
                            : "satuan"
                        }`}
                      >
                        {menu.kategori}
                      </span>
                    </td>

                    {/* HARGA */}
                    <td className="km-price">
                      {menu.kategori === "Prasmanan" ? (
                        <div className="km-price-options">
                          {[...menu.pilihanHarga]
                            .sort((a, b) => a - b)
                            .map((harga) => (
                              <span key={harga}>
                                {Number(harga).toLocaleString(
                                  "id-ID"
                                )}
                              </span>
                            ))}
                        </div>
                      ) : (
                        rupiah(menu.harga)
                      )}
                    </td>

                    {/* STOK TANPA IKON */}
                    <td>
                      <span
                        className={`km-stock ${
                          menu.stok === 0
                            ? "habis"
                            : ""
                        }`}
                      >
                        {menu.stok}
                      </span>
                    </td>

                    {/* STATUS */}
                    <td>
                      <button
                        type="button"
                        className={`km-status ${
                          aktif
                            ? "aktif"
                            : "nonaktif"
                        }`}
                        title={
                          menu.stok === 0
                            ? "Edit stok terlebih dahulu"
                            : "Ubah status menu"
                        }
                        onClick={() =>
                          ubahKetersediaan(menu.id)
                        }
                      >
                        <span className="km-status-dot" />

                        {aktif
                          ? "Tersedia"
                          : "Habis / Nonaktif"}
                      </button>
                    </td>

                    {/* AKSI */}
                    <td>
                      <div className="km-actions">

                        <button
                          type="button"
                          className="km-icon-btn edit"
                          title="Edit menu"
                          aria-label={`Edit ${menu.nama}`}
                          onClick={() => bukaEdit(menu)}
                        >
                          <Pencil size={16} />
                        </button>

                        <button
                          type="button"
                          className="km-icon-btn delete"
                          title="Hapus menu"
                          aria-label={`Hapus ${menu.nama}`}
                          onClick={() =>
                            setHapusId(menu.id)
                          }
                        >
                          <Trash2 size={16} />
                        </button>

                      </div>
                    </td>

                  </tr>
                );
              })}
            </tbody>

          </table>

          {menuDitampilkan.length === 0 && (
            <div className="km-empty">
              <UtensilsCrossed size={28} />
              <strong>Menu tidak ditemukan</strong>
              <p>
                Coba ubah kata pencarian atau filter.
              </p>
            </div>
          )}

        </div>
      </section>

      {/* =====================================
          MODAL TAMBAH / EDIT MENU
      ===================================== */}

      {modalOpen && (
        <div
          className="km-modal-backdrop"
          onMouseDown={(event) => {
            if (event.target === event.currentTarget) {
              tutupModal();
            }
          }}
        >
          <div
            className="km-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="km-modal-title"
          >

            <div className="km-modal-header">
              <div>
                <h2 id="km-modal-title">
                  {editId !== null
                    ? "Edit Menu"
                    : "Tambah Menu"}
                </h2>
                <p>Isi informasi menu tenant.</p>
              </div>

              <button
                type="button"
                className="km-modal-close"
                onClick={tutupModal}
                aria-label="Tutup modal"
              >
                <X size={19} />
              </button>
            </div>

            <form onSubmit={simpanMenu}>
              <div className="km-form-content">

                {error && (
                  <div className="km-error" role="alert">
                    <AlertCircle size={16} />
                    {error}
                  </div>
                )}

                {/* NAMA MENU */}
                <label className="km-field">
                  <span>Nama Menu *</span>

                  <input
                    type="text"
                    maxLength={100}
                    required
                    value={form.nama}
                    placeholder="Contoh: Paket Prasmanan"
                    onChange={(event) =>
                      ubahForm(
                        "nama",
                        event.target.value
                      )
                    }
                  />
                </label>

                {/* KATEGORI */}
                <label className="km-field">
                  <span>Kategori *</span>

                  <select
                    value={form.kategori}
                    onChange={(event) =>
                      ubahForm(
                        "kategori",
                        event.target.value
                      )
                    }
                  >
                    <option value="Satuan">
                      Satuan
                    </option>
                    <option value="Prasmanan">
                      Prasmanan
                    </option>
                  </select>
                </label>

                {/* HARGA MENU SATUAN */}
                {form.kategori === "Satuan" && (
                  <label className="km-field">
                    <span>Harga Menu (Rp) *</span>

                    <input
                      type="number"
                      min="1"
                      step="1"
                      required
                      value={form.harga}
                      placeholder="Contoh: 18000"
                      onChange={(event) =>
                        ubahForm(
                          "harga",
                          event.target.value
                        )
                      }
                    />
                  </label>
                )}

                {/* HARGA PRASMANAN */}
                {form.kategori === "Prasmanan" && (
                  <div className="km-field">

                    <span>
                      Pilihan Harga Prasmanan *
                    </span>

                    <p className="km-form-note">
                      Masukkan pilihan harga per porsi
                      atau lauk.
                    </p>

                    <div className="km-price-editor">
                      {form.pilihanHarga.map(
                        (harga, index) => (
                          <div
                            key={index}
                            className="km-price-editor-row"
                          >
                            <span className="km-rupiah-prefix">
                              Rp
                            </span>

                            <input
                              type="number"
                              min="1"
                              step="1"
                              value={harga}
                              placeholder="Contoh: 2000"
                              aria-label={`Pilihan harga ${
                                index + 1
                              }`}
                              onChange={(event) =>
                                ubahPilihanHarga(
                                  index,
                                  event.target.value
                                )
                              }
                            />

                            <button
                              type="button"
                              className="km-remove-price"
                              title="Hapus pilihan harga"
                              aria-label={`Hapus pilihan harga ${
                                index + 1
                              }`}
                              onClick={() =>
                                hapusPilihanHarga(index)
                              }
                            >
                              <Trash2 size={15} />
                            </button>
                          </div>
                        )
                      )}
                    </div>

                    <button
                      type="button"
                      className="km-add-price"
                      onClick={tambahPilihanHarga}
                    >
                      <Plus size={15} />
                      Tambah Pilihan Harga
                    </button>

                  </div>
                )}

                {/* STOK DAN STATUS */}
                <div className="km-form-row">

                  <label className="km-field">
                    <span>Stok *</span>

                    <input
                      type="number"
                      min="0"
                      step="1"
                      required
                      value={form.stok}
                      placeholder="Contoh: 20"
                      onChange={(event) =>
                        ubahForm(
                          "stok",
                          event.target.value
                        )
                      }
                    />
                  </label>

                  <label className="km-field">
                    <span>Status Menu</span>

                    <select
                      value={
                        form.tersedia
                          ? "Tersedia"
                          : "Nonaktif"
                      }
                      onChange={(event) =>
                        ubahForm(
                          "tersedia",
                          event.target.value ===
                            "Tersedia"
                        )
                      }
                    >
                      <option value="Tersedia">
                        Tersedia
                      </option>
                      <option value="Nonaktif">
                        Nonaktif
                      </option>
                    </select>
                  </label>

                </div>

                {/* DESKRIPSI */}
                <label className="km-field">
                  <span>Deskripsi</span>

                  <textarea
                    rows={3}
                    maxLength={300}
                    value={form.deskripsi}
                    placeholder="Deskripsi menu..."
                    onChange={(event) =>
                      ubahForm(
                        "deskripsi",
                        event.target.value
                      )
                    }
                  />
                </label>

                <p className="km-form-note">
                  Menu dengan stok 0 otomatis
                  berstatus tidak tersedia.
                </p>

              </div>

              {/* TOMBOL SIMPAN */}
              <div className="km-modal-footer">

                <button
                  type="button"
                  className="km-cancel-btn"
                  onClick={tutupModal}
                >
                  Batal
                </button>

                <button
                  type="submit"
                  className="km-primary-btn"
                >
                  <Save size={16} />

                  {editId !== null
                    ? "Simpan Perubahan"
                    : "Tambah Menu"}
                </button>

              </div>
            </form>

          </div>
        </div>
      )}

      {/* =====================================
          KONFIRMASI HAPUS
      ===================================== */}

      {hapusId !== null && (
        <div className="km-modal-backdrop">

          <div
            className="km-confirm-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="km-confirm-title"
          >

            <div className="km-confirm-icon">
              <Trash2 size={26} />
            </div>

            <h2 id="km-confirm-title">
              Hapus Menu?
            </h2>

            <p>
              Menu yang dihapus akan hilang dari
              daftar pada pratinjau ini.
            </p>

            <div className="km-confirm-actions">

              <button
                type="button"
                className="km-cancel-btn"
                onClick={() =>
                  setHapusId(null)
                }
              >
                Batal
              </button>

              <button
                type="button"
                className="km-danger-btn"
                onClick={konfirmasiHapus}
              >
                <Trash2 size={15} />
                Hapus Menu
              </button>

            </div>
          </div>
        </div>
      )}

    </div>
  );
}
