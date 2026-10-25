import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import "../../styles/MenuTenant.css";

const API_URL = import.meta.env.VITE_API_URL || "http://127.0.0.1:8000";

const HARGA_NASI_DEFAULT = [2000, 3000, 4000, 5000];
const GAMBAR_DEFAULT =
  "https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600";

function formatRupiah(harga) {
  return "Rp" + Number(harga || 0).toLocaleString("id-ID");
}

function ambilArray(hasil) {
  if (Array.isArray(hasil)) return hasil;
  if (Array.isArray(hasil?.data)) return hasil.data;
  if (Array.isArray(hasil?.menus)) return hasil.menus;
  if (Array.isArray(hasil?.data?.menus)) return hasil.data.menus;
  return [];
}

function ambilObjek(hasil) {
  if (hasil?.data && !Array.isArray(hasil.data)) {
    return hasil.data.tenant ?? hasil.data;
  }

  return hasil?.tenant ?? hasil;
}

function normalisasiOpsiHarga(opsi) {
  if (Array.isArray(opsi)) return opsi;

  if (typeof opsi === "string") {
    try {
      const hasil = JSON.parse(opsi);
      return Array.isArray(hasil) ? hasil : [];
    } catch {
      return [];
    }
  }

  return [];
}

function normalisasiTenant(data) {
  return {
    id: data?.id,
    nama: data?.nama ?? "Tenant",
    jenis: data?.jenis ?? "Satuan",
    kategori: data?.kategori ?? data?.keterangan ?? "",
  };
}

function normalisasiMenu(item, tenant) {
  const nama = item.nama_menu ?? item.nama ?? "Menu";
  const jenisTenant = String(tenant.jenis).toLowerCase();
  const jenisMenu = String(item.jenis_menu ?? "satuan").toLowerCase();
  const tipeAsli = String(item.tipe_harga ?? "tetap").toLowerCase();
  const opsiDatabase = normalisasiOpsiHarga(item.opsi_harga);
  const namaNasi = /\bnasi\b/i.test(nama);

  let tipeHarga;

  if (
    ["potong", "per_potong"].includes(tipeAsli) ||
    jenisMenu === "potong"
  ) {
    tipeHarga = "potong";
  } else if (
    ["nominal", "fleksibel", "pilihan", "pilihan_nominal"].includes(
      tipeAsli
    ) ||
    opsiDatabase.length > 0
  ) {
    tipeHarga = "nominal";
  } else if (jenisTenant.includes("prasmanan") && namaNasi) {
    tipeHarga = "nominal";
  } else if (jenisTenant.includes("prasmanan")) {
    tipeHarga = "potong";
  } else {
    tipeHarga = "tetap";
  }

  let gambar = item.foto ?? item.gambar ?? "";

  if (gambar && !/^https?:\/\//i.test(gambar)) {
    gambar = `${API_URL}/storage/${gambar.replace(/^\/+/, "")}`;
  }

  const opsi = opsiDatabase
    .map((opsiItem) => {
      if (
        typeof opsiItem === "number" ||
        typeof opsiItem === "string"
      ) {
        return Number(opsiItem);
      }

      return Number(
        opsiItem?.harga ??
          opsiItem?.price ??
          opsiItem?.value ??
          opsiItem?.nominal ??
          0
      );
    })
    .filter((harga) => Number.isFinite(harga) && harga > 0);

  return {
    id: item.id,
    tenantId: Number(item.tenant_id),
    nama,
    harga: Number(item.harga ?? 0),
    tipeHarga,
    opsiHarga:
      tipeHarga === "nominal"
        ? opsi.length
          ? opsi
          : HARGA_NASI_DEFAULT
        : [],
    gambar: gambar || GAMBAR_DEFAULT,
    deskripsi: item.deskripsi ?? "",
    status: String(item.status ?? "tersedia").toLowerCase(),
    stok: Number(item.stok ?? 0),
  };
}

function MenuTenant() {
  const navigate = useNavigate();
  const { id } = useParams();
  const tenantId = Number(id);

  const [tenant, setTenant] = useState(null);
  const [daftarMenu, setDaftarMenu] = useState([]);
  const [hargaDipilih, setHargaDipilih] = useState({});
  const [jumlahDipilih, setJumlahDipilih] = useState({});
  const [sedangMemuat, setSedangMemuat] = useState(true);
  const [sedangMenambah, setSedangMenambah] = useState(null);
  const [errorMenu, setErrorMenu] = useState("");
  const [errorKeranjang, setErrorKeranjang] = useState("");
  const [pesanSukses, setPesanSukses] = useState("");

  const isPrasmanan = String(tenant?.jenis ?? "")
    .toLowerCase()
    .includes("prasmanan");

  useEffect(() => {
    const controller = new AbortController();

    async function ambilData() {
      setSedangMemuat(true);
      setErrorMenu("");
      setTenant(null);
      setDaftarMenu([]);
      setHargaDipilih({});
      setJumlahDipilih({});

      try {
        const [responseTenant, responseMenu] = await Promise.all([
          fetch(`${API_URL}/api/tenants/${tenantId}`, {
            headers: { Accept: "application/json" },
            signal: controller.signal,
          }),
          fetch(`${API_URL}/api/menus?tenant_id=${tenantId}`, {
            headers: { Accept: "application/json" },
            signal: controller.signal,
          }),
        ]);

        if (!responseTenant.ok) {
          throw new Error("Data tenant gagal dimuat dari Laravel.");
        }

        if (!responseMenu.ok) {
          throw new Error("Data menu gagal dimuat dari Laravel.");
        }

        const [hasilTenant, hasilMenu] = await Promise.all([
          responseTenant.json(),
          responseMenu.json(),
        ]);

        const tenantNormal = normalisasiTenant(
          ambilObjek(hasilTenant)
        );

        if (!tenantNormal.id) {
          throw new Error("Data tenant tidak ditemukan.");
        }

        const menuDatabase = ambilArray(hasilMenu)
          .filter(
            (item) => Number(item.tenant_id) === tenantId
          )
          .map((item) => normalisasiMenu(item, tenantNormal));

        setTenant(tenantNormal);
        setDaftarMenu(menuDatabase);
      } catch (error) {
        if (error.name !== "AbortError") {
          console.error("Gagal memuat halaman menu:", error);
          setErrorMenu(
            error.message ||
              "Tidak dapat terhubung ke server Laravel."
          );
        }
      } finally {
        if (!controller.signal.aborted) {
          setSedangMemuat(false);
        }
      }
    }

    if (Number.isFinite(tenantId) && tenantId > 0) {
      ambilData();
    } else {
      setErrorMenu("ID tenant tidak valid.");
      setSedangMemuat(false);
    }

    return () => controller.abort();
  }, [tenantId]);

  function pilihHarga(menu, harga) {
    setHargaDipilih((sebelumnya) => ({
      ...sebelumnya,
      [menu.id]: harga,
    }));

    setErrorKeranjang("");
    setPesanSukses("");
  }

  function ubahJumlah(menu, perubahan) {
    setJumlahDipilih((sebelumnya) => ({
      ...sebelumnya,
      [menu.id]: Math.min(
        99,
        Math.max(
          1,
          (sebelumnya[menu.id] ?? 1) + perubahan
        )
      ),
    }));
  }

  async function tambahKeKeranjang(menu) {
    if (sedangMenambah) return;

    if (menu.status !== "tersedia" || menu.stok <= 0) {
      setErrorKeranjang("Maaf, menu ini sedang habis.");
      return;
    }

    const token = localStorage.getItem("kantinkita_token");

    if (!token) {
      setErrorKeranjang("Silakan login terlebih dahulu.");
      return;
    }

    const harga =
      menu.tipeHarga === "nominal"
        ? hargaDipilih[menu.id]
        : menu.harga;

    if (!Number.isFinite(Number(harga)) || Number(harga) <= 0) {
      setErrorKeranjang(
        menu.tipeHarga === "nominal"
          ? "Pilih nominal nasi terlebih dahulu."
          : "Harga menu belum tersedia."
      );
      return;
    }

    const quantity = jumlahDipilih[menu.id] ?? 1;

    setSedangMenambah(menu.id);
    setErrorKeranjang("");
    setPesanSukses("");

    try {
      const response = await fetch(
        `${API_URL}/api/cart/items`,
        {
          method: "POST",
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            tenant_id: tenantId,
            menu_id: menu.id,
            quantity,
            selected_price: Number(harga),
            jenis_harga: isPrasmanan
              ? "prasmanan"
              : "satuan",
          }),
        }
      );

      const hasil = await response.json().catch(() => ({}));

      if (!response.ok) {
        const detailValidasi = hasil.errors
          ? Object.values(hasil.errors).flat().join(" ")
          : "";

        throw new Error(
          detailValidasi ||
            hasil.message ||
            `Gagal menambahkan menu (${response.status}).`
        );
      }

      setPesanSukses(
        `${menu.nama} berhasil ditambahkan ke keranjang.`
      );
    } catch (error) {
      console.error("Gagal menambahkan menu:", error);
      setErrorKeranjang(
        error.message || "Terjadi kesalahan."
      );
    } finally {
      setSedangMenambah(null);
    }
  }

  if (!Number.isFinite(tenantId) || tenantId <= 0) {
    return (
      <div className="menu-not-found">
        <h2>Tenant tidak ditemukan</h2>
        <button
          type="button"
          onClick={() => navigate("/beranda")}
        >
          Kembali ke Dashboard
        </button>
      </div>
    );
  }

  return (
    <div className="menu-tenant-page">
      <header className="menu-navbar">
        <button
          type="button"
          className="menu-brand"
          onClick={() => navigate("/beranda")}
        >
          <span className="menu-logo">K</span>
          <span>
            Kantin<span>Kita</span>
          </span>
        </button>
      </header>

      <main className="menu-main">
        <button
          type="button"
          className="menu-back-button"
          onClick={() => navigate("/beranda")}
        >
          ← Kembali ke Daftar Tenant
        </button>

        {sedangMemuat ? (
          <p>Memuat data tenant dan menu...</p>
        ) : errorMenu ? (
          <section className="menu-cart-error" role="alert">
            <p>{errorMenu}</p>
            <button
              type="button"
              onClick={() => window.location.reload()}
            >
              Coba Lagi
            </button>
          </section>
        ) : (
          <>
            <section className="menu-heading">
              <span className="menu-eyebrow">MENU TENANT</span>
              <h1>{tenant?.nama}</h1>
              <p>
                {tenant?.kategori ||
                  "Pilih makanan yang kamu inginkan."}
              </p>

              <div className="menu-tenant-info">
                <span>🍴 {tenant?.jenis}</span>
                <span>
                  ●{" "}
                  {
                    daftarMenu.filter(
                      (menu) =>
                        menu.status === "tersedia" &&
                        menu.stok > 0
                    ).length
                  }{" "}
                  menu tersedia
                </span>
              </div>
            </section>

            {errorKeranjang && (
              <div className="menu-cart-error" role="alert">
                {errorKeranjang}
              </div>
            )}

            {pesanSukses && (
              <div className="menu-cart-success" role="status">
                {pesanSukses}
              </div>
            )}

            <section className="menu-list-section">
              <div className="menu-list-heading">
                <div>
                  <h2>Daftar Menu</h2>
                  <p>
                    {isPrasmanan
                      ? "Pilih nominal nasi dan lauk sesuai kebutuhan."
                      : "Harga menu dihitung per porsi."}
                  </p>
                </div>
                <span>{daftarMenu.length} menu</span>
              </div>

              {daftarMenu.length === 0 ? (
                <div className="tenant-empty">
                  <p>Belum ada menu untuk tenant ini.</p>
                  <span>
                    Menu akan tampil setelah disimpan dan
                    diaktifkan di database.
                  </span>
                </div>
              ) : (
                <div className="menu-grid">
                  {daftarMenu.map((menu) => {
                    const habis =
                      menu.status !== "tersedia" ||
                      menu.stok <= 0;

                    const jumlah =
                      jumlahDipilih[menu.id] ?? 1;

                    const nominalNasi =
                      isPrasmanan &&
                      menu.tipeHarga === "nominal";

                    return (
                      <article
                        className="menu-card"
                        key={menu.id}
                      >
                        <img
                          src={menu.gambar}
                          alt={menu.nama}
                          className="menu-food-image"
                          loading="lazy"
                          onError={(event) => {
                            event.currentTarget.onerror = null;
                            event.currentTarget.src =
                              GAMBAR_DEFAULT;
                          }}
                        />

                        <div className="menu-card-body">
                          <h3>{menu.nama}</h3>

                          {menu.deskripsi && (
                            <p className="menu-description">
                              {menu.deskripsi}
                            </p>
                          )}

                          <p
                            className={`menu-status ${
                              habis ? "habis" : "tersedia"
                            }`}
                          >
                            {habis ? "Habis" : "Tersedia"}
                          </p>

                          {nominalNasi ? (
                            <>
                              <p className="prasmanan-label">
                                Pilih nominal nasi
                              </p>

                              <div className="prasmanan-harga-grid">
                                {menu.opsiHarga.map((harga) => (
                                  <button
                                    type="button"
                                    key={harga}
                                    disabled={
                                      habis ||
                                      Boolean(sedangMenambah)
                                    }
                                    className={`prasmanan-harga-option ${
                                      hargaDipilih[menu.id] === harga
                                        ? "dipilih"
                                        : ""
                                    }`}
                                    onClick={() =>
                                      pilihHarga(menu, harga)
                                    }
                                  >
                                    {formatRupiah(harga)}
                                  </button>
                                ))}
                              </div>
                            </>
                          ) : (
                            <p className="menu-price">
                              {formatRupiah(menu.harga)}
                              {isPrasmanan
                                ? " / potong"
                                : " / porsi"}
                            </p>
                          )}

                          <div className="menu-card-bottom">
                            <div className="menu-quantity-control">
                              <button
                                type="button"
                                aria-label={`Kurangi jumlah ${menu.nama}`}
                                disabled={jumlah <= 1 || habis}
                                onClick={() =>
                                  ubahJumlah(menu, -1)
                                }
                              >
                                −
                              </button>

                              <span>{jumlah}</span>

                              <button
                                type="button"
                                aria-label={`Tambah jumlah ${menu.nama}`}
                                disabled={habis || jumlah >= 99}
                                onClick={() =>
                                  ubahJumlah(menu, 1)
                                }
                              >
                                +
                              </button>
                            </div>

                            <button
                              type="button"
                              className="menu-add-button"
                              disabled={
                                habis ||
                                Boolean(sedangMenambah)
                              }
                              onClick={() =>
                                tambahKeKeranjang(menu)
                              }
                            >
                              {habis
                                ? "Menu Habis"
                                : sedangMenambah === menu.id
                                  ? "Memproses..."
                                  : "+ Tambah"}
                            </button>
                          </div>

                          {nominalNasi &&
                            hargaDipilih[menu.id] && (
                              <p className="menu-selected-price">
                                Nominal dipilih:{" "}
                                <strong>
                                  {formatRupiah(
                                    hargaDipilih[menu.id]
                                  )}
                                </strong>
                              </p>
                            )}
                        </div>
                      </article>
                    );
                  })}
                </div>
              )}
            </section>
          </>
        )}
      </main>
    </div>
  );
}

export default MenuTenant;