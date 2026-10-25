import { useCallback, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import "../../styles/Keranjang.css";

const API_URL =
  import.meta.env.VITE_API_URL || "http://127.0.0.1:8000";

function formatRupiah(angka) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(Number(angka) || 0);
}

function ambilItems(hasil) {
  const items =
    hasil?.items ??
    hasil?.cart?.items ??
    hasil?.data?.items ??
    hasil?.data?.cart?.items ??
    hasil?.data ??
    [];

  return Array.isArray(items) ? items : [];
}

function normalisasiItem(item) {
  const menu = item.menu ?? item.menus ?? {};
  const tenant = item.tenant ?? menu.tenant ?? {};

  return {
    ...item,
    id: item.id,
    menu_id: item.menu_id ?? menu.id ?? item.id_menu,
    nama_menu:
      item.nama_menu ??
      menu.nama_menu ??
      menu.nama ??
      item.nama ??
      "Menu",
    nama_tenant:
      item.nama_tenant ??
      tenant.nama ??
      menu.nama_tenant ??
      "Tenant KantinKita",
    foto: item.foto ?? menu.foto ?? menu.gambar ?? "",
    harga: Number(
      item.selected_price ??
        item.harga_satuan ??
        item.harga ??
        menu.harga ??
        0
    ),
    quantity: Number(item.quantity ?? item.qty ?? 1),
    jenis_harga: item.jenis_harga ?? "satuan",
    tipe_harga: item.tipe_harga ?? menu.tipe_harga ?? "",
    opsi_harga: item.opsi_harga ?? menu.opsi_harga ?? null,
  };
}

function Keranjang() {
  const navigate = useNavigate();

  const [items, setItems] = useState([]);
  const [itemDipilih, setItemDipilih] = useState([]);
  const [loading, setLoading] = useState(true);
  const [itemDiproses, setItemDiproses] = useState(null);
  const [error, setError] = useState("");

  const token = localStorage.getItem("kantinkita_token");

  const requestAPI = useCallback(
    async (path, options = {}) => {
      const response = await fetch(`${API_URL}${path}`, {
        ...options,
        headers: {
          Accept: "application/json",
          ...(token ? { Authorization: `Bearer ${token}` } : {}),
          ...(options.body
            ? { "Content-Type": "application/json" }
            : {}),
          ...options.headers,
        },
      });

      if (response.status === 401) {
        throw new Error(
          "Sesi login berakhir. Silakan login kembali."
        );
      }

      if (!response.ok) {
        const hasil = await response.json().catch(() => ({}));
        throw new Error(
          hasil.message || "Permintaan gagal. Silakan coba lagi."
        );
      }

      if (response.status === 204) return null;

      return response.json();
    },
    [token]
  );

  const muatKeranjang = useCallback(async () => {
    setLoading(true);
    setError("");

    try {
      if (!token) {
        setItems([]);
        setItemDipilih([]);
        setError(
          "Token login tidak ditemukan. Silakan login kembali."
        );
        return;
      }

      const hasil = await requestAPI("/api/cart");
      const data = ambilItems(hasil).map(normalisasiItem);

      setItems(data);
      setItemDipilih(data.map((item) => item.id));
    } catch (err) {
      setError(err.message || "Gagal memuat keranjang.");
    } finally {
      setLoading(false);
    }
  }, [requestAPI, token]);

  useEffect(() => {
    muatKeranjang();
  }, [muatKeranjang]);

  const semuaDipilih =
    items.length > 0 && itemDipilih.length === items.length;

  function toggleSemua() {
    setItemDipilih(
      semuaDipilih ? [] : items.map((item) => item.id)
    );
  }

  function toggleItem(id) {
    setItemDipilih((prev) =>
      prev.includes(id)
        ? prev.filter((itemId) => itemId !== id)
        : [...prev, id]
    );
  }

  async function ubahJumlah(item, perubahan) {
    const jumlahBaru = item.quantity + perubahan;

    if (jumlahBaru < 1) return;

    setItemDiproses(item.id);
    setError("");

    try {
      await requestAPI(`/api/cart/items/${item.id}`, {
        method: "PUT",
        body: JSON.stringify({ quantity: jumlahBaru }),
      });

      setItems((prev) =>
        prev.map((data) =>
          data.id === item.id
            ? { ...data, quantity: jumlahBaru }
            : data
        )
      );
    } catch (err) {
      setError(err.message || "Gagal mengubah jumlah item.");
    } finally {
      setItemDiproses(null);
    }
  }

  async function hapusItem(item) {
    const konfirmasi = window.confirm(
      `Hapus ${item.nama_menu} dari keranjang?`
    );

    if (!konfirmasi) return;

    setItemDiproses(item.id);
    setError("");

    try {
      await requestAPI(`/api/cart/items/${item.id}`, {
        method: "DELETE",
      });

      setItems((prev) =>
        prev.filter((data) => data.id !== item.id)
      );

      setItemDipilih((prev) =>
        prev.filter((id) => id !== item.id)
      );
    } catch (err) {
      setError(err.message || "Gagal menghapus item.");
    } finally {
      setItemDiproses(null);
    }
  }

  const itemsTerpilih = items.filter((item) =>
    itemDipilih.includes(item.id)
  );

  function lanjutCheckout() {
    if (itemsTerpilih.length === 0) {
      setError("Pilih minimal satu menu untuk melanjutkan.");
      return;
    }

    navigate("/checkout", {
      state: { itemDipilih: itemsTerpilih },
    });
  }

  return (
    <div className="keranjang-page">
      <header className="keranjang-navbar">
        <button
          className="keranjang-brand"
          onClick={() => navigate("/beranda")}
          aria-label="Kembali ke dashboard KantinKita"
        >
          <span className="keranjang-logo">K</span>
          <span>
            Kantin<span className="brand-highlight">Kita</span>
          </span>
        </button>

        <div className="keranjang-nav-title">
          <span className="keranjang-nav-icon">🛒</span>
          <span>Keranjang Belanja</span>
        </div>

        <button
          className="keranjang-nav-back"
          onClick={() => navigate("/beranda")}
        >
          ← Dashboard
        </button>
      </header>

      <main className="keranjang-content">
        <div className="keranjang-breadcrumb">
          <button onClick={() => navigate("/beranda")}>
            Beranda
          </button>
          <span>/</span>
          <span>Keranjang</span>
        </div>

        <section className="keranjang-heading">
          <div>
            <span className="keranjang-eyebrow">
              PESANAN FAVORITMU
            </span>
            <h1>Keranjang Belanja</h1>
            <p>
              Berikut menu yang sudah kamu masukkan ke keranjang.
            </p>
          </div>

          <div className="keranjang-heading-icon">🛍️</div>
        </section>

        {error && (
          <div className="keranjang-alert" role="alert">
            <span>{error}</span>
            <button onClick={muatKeranjang}>Muat ulang</button>
          </div>
        )}

        {loading ? (
          <div className="keranjang-state">
            <div className="keranjang-spinner" />
            <p>Memuat keranjang kamu...</p>
          </div>
        ) : items.length === 0 ? (
          <section className="keranjang-kosong">
            <div className="keranjang-kosong-icon">🛒</div>
            <h2>Yah, keranjangmu masih kosong!</h2>
            <p>
              Kamu belum memasukkan menu ke keranjang.
              Yuk, cari menu favoritmu di KantinKita.
            </p>
            <button
              className="keranjang-primary"
              onClick={() => navigate("/beranda")}
            >
              Jelajahi Menu →
            </button>
          </section>
        ) : (
          <div className="keranjang-layout keranjang-layout-single">
            <section className="keranjang-list">
              <div className="keranjang-list-heading">
                <label className="keranjang-select-all">
                  <input
                    type="checkbox"
                    checked={semuaDipilih}
                    onChange={toggleSemua}
                  />
                  <span>Pilih semua</span>
                </label>

                <span className="keranjang-count">
                  {items.length} jenis menu
                </span>
              </div>

              {items.map((item) => (
                <article className="keranjang-item" key={item.id}>
                  <div className="keranjang-item-check">
                    <input
                      type="checkbox"
                      checked={itemDipilih.includes(item.id)}
                      onChange={() => toggleItem(item.id)}
                      aria-label={`Pilih ${item.nama_menu}`}
                    />
                  </div>

                  <div className="keranjang-item-image">
                    {item.foto ? (
                      <img
                        src={item.foto}
                        alt={item.nama_menu}
                        onError={(event) => {
                          event.currentTarget.style.display = "none";
                        }}
                      />
                    ) : (
                      <span>🍱</span>
                    )}
                  </div>

                  <div className="keranjang-item-info">
                    <span className="keranjang-tenant">
                      {item.nama_tenant}
                    </span>

                    <h3>{item.nama_menu}</h3>

                    <p className="keranjang-price">
                      {formatRupiah(item.harga)}
                      {item.jenis_harga === "satuan" && (
                        <span className="keranjang-price-unit">
                          {" "}/ porsi
                        </span>
                      )}
                    </p>

                    <button
                      className="keranjang-delete"
                      onClick={() => hapusItem(item)}
                      disabled={itemDiproses === item.id}
                    >
                      <span>🗑</span> Hapus
                    </button>
                  </div>

                  <div className="keranjang-item-side">
                    <div className="keranjang-quantity">
                      <button
                        aria-label={`Kurangi ${item.nama_menu}`}
                        disabled={
                          item.quantity <= 1 ||
                          itemDiproses === item.id
                        }
                        onClick={() => ubahJumlah(item, -1)}
                      >
                        −
                      </button>

                      <span>{item.quantity}</span>

                      <button
                        aria-label={`Tambah ${item.nama_menu}`}
                        disabled={itemDiproses === item.id}
                        onClick={() => ubahJumlah(item, 1)}
                      >
                        +
                      </button>
                    </div>

                    <strong>
                      {formatRupiah(item.harga * item.quantity)}
                    </strong>
                  </div>
                </article>
              ))}

              <div className="keranjang-bottom-actions">
                <button
                  className="keranjang-continue"
                  onClick={() => navigate("/beranda")}
                >
                  ← Tambah menu lainnya
                </button>

                <button
                  className="keranjang-primary keranjang-checkout-bottom"
                  onClick={lanjutCheckout}
                  disabled={itemsTerpilih.length === 0}
                >
                  Lanjutkan Pembayaran →
                </button>
              </div>
            </section>
          </div>
        )}
      </main>
    </div>
  );
}

export default Keranjang;