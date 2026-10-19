
import { useEffect, useState } from "react";
import {
  ReceiptText, Wallet, Search, Banknote, Info,
  CheckCircle2, X, RefreshCw
} from "lucide-react";
import { useTenants } from "../../context/useTenants.js";
import { apiRequest } from "../../services/api.js";
import "../../styles/TransaksiOffline.css";

const rupiah = (value) =>
  `Rp ${Number(value || 0).toLocaleString("id-ID")}`;

const tanggalLokal = () => {
  const d = new Date();
  const tahun = d.getFullYear();
  const bulan = String(d.getMonth() + 1).padStart(2, "0");
  const hari = String(d.getDate()).padStart(2, "0");
  return `${tahun}-${bulan}-${hari}`;
};

function TransaksiOffline() {
  const {
    tenants = [],
    loading: tenantLoading,
    error: tenantError,
    refreshTenants
  } = useTenants();

  const [tenantId, setTenantId] = useState("");
  const [kode, setKode] = useState("");
  const [filterTenant, setFilterTenant] = useState("");
  const [transactions, setTransactions] = useState([]);
  const [loadingTransactions, setLoadingTransactions] = useState(true);
  const [transactionError, setTransactionError] = useState("");
  const [refreshKey, setRefreshKey] = useState(0);
  const [found, setFound] = useState(null);
  const [message, setMessage] = useState("");
  const [searching, setSearching] = useState(false);
  const [confirming, setConfirming] = useState(false);
  const [uangDiterima, setUangDiterima] = useState("");

  // Memuat transaksi yang dikirim Tenant
  useEffect(() => {
    let active = true;

    const fetchTransactions = async () => {
      try {
        const result = await apiRequest("/admin/offline-transactions");
        if (!active) return;
        const data = Array.isArray(result)
          ? result
          : Array.isArray(result?.data)
            ? result.data
            : [];
        setTransactions(data);
        setTransactionError("");
      } catch (error) {
        if (!active) return;
        setTransactionError(error.message || "Gagal memuat transaksi.");
      } finally {
        if (active) setLoadingTransactions(false);
      }
    };

    fetchTransactions();
    return () => { active = false; };
  }, [refreshKey]);

  const refreshTransactions = () => {
    setLoadingTransactions(true);
    setRefreshKey((prev) => prev + 1);
  };

  const todayTransactions = transactions.filter(
    (item) => String(item.created_at || "").slice(0, 10) === tanggalLokal()
  );

  const totalPendapatan = todayTransactions
    .filter((item) => item.status === "Lunas")
    .reduce((sum, item) => sum + Number(item.total || 0), 0);

  const menungguPembayaran = transactions.filter(
    (item) => item.status === "Menunggu Pembayaran"
  ).length;

  const filteredTransactions = filterTenant
    ? transactions.filter(
        (item) => String(item.tenant_id) === String(filterTenant)
      )
    : transactions;

  const handleSearch = async (event) => {
    event.preventDefault();
    setFound(null);
    setMessage("");
    setUangDiterima("");

    if (!tenantId || !kode.trim()) {
      setMessage("Pilih tenant dan masukkan kode transaksi terlebih dahulu.");
      return;
    }

    try {
      setSearching(true);
      const query = new URLSearchParams({
        tenant_id: tenantId,
        kode: kode.trim()
      });
      const result = await apiRequest(
        `/admin/offline-transactions/search?${query.toString()}`
      );
      const data = result?.data ?? result;

      if (!data?.id) {
        throw new Error("Transaksi tidak ditemukan.");
      }

      setFound({
        ...data,
        items: Array.isArray(data.items) ? data.items : []
      });
    } catch (error) {
      setMessage(error.message || "Gagal mencari transaksi.");
    } finally {
      setSearching(false);
    }
  };

  const totalTagihan = Number(found?.total || 0);
  const nominalDiterima = Number(uangDiterima || 0);
  const uangCukup = uangDiterima !== "" &&
    Number.isInteger(nominalDiterima) &&
    nominalDiterima >= totalTagihan;
  const kembalian = uangCukup ? nominalDiterima - totalTagihan : 0;

  const handleConfirm = async () => {
    if (!found || found.status !== "Menunggu Pembayaran" || confirming) return;

    if (!uangCukup) {
      setMessage("Uang diterima harus sama dengan atau lebih besar dari total tagihan.");
      return;
    }

    try {
      setConfirming(true);
      setMessage("");

      const result = await apiRequest(
        `/admin/offline-transactions/${found.id}/confirm`,
        {
          method: "POST",
          body: JSON.stringify({ uang_diterima: nominalDiterima })
        }
      );

      if (result?.data?.status !== "Lunas") {
        throw new Error("Status pembayaran belum dapat dipastikan.");
      }

      setFound((prev) => ({
        ...prev,
        ...result.data,
        status: "Lunas"
      }));
      setMessage("Pembayaran berhasil dikonfirmasi. Status sekarang Lunas.");
      refreshTransactions();
    } catch (error) {
      setMessage(error.message || "Gagal mengonfirmasi pembayaran.");
    } finally {
      setConfirming(false);
    }
  };

  return (
    <div className="offline-page">
      {/* JUDUL */}
      <div className="offline-heading">
        <h1>Transaksi Offline</h1>
        <p>Cari kode transaksi dari Tenant dan konfirmasi pembayaran tunai pelanggan.</p>
      </div>

      {/* STATISTIK */}
      <div className="offline-stats">
        <div className="offline-stat-card">
          <span className="offline-stat-icon">
            <ReceiptText size={21} />
          </span>
          <div>
            <small>Transaksi Offline Hari Ini</small>
            <strong>
              {loadingTransactions || transactionError
                ? "-"
                : `${todayTransactions.length} Transaksi`}
            </strong>
          </div>
        </div>
        <div className="offline-stat-card">
          <span className="offline-stat-icon">
            <Wallet size={21} />
          </span>
          <div>
            <small>Pendapatan Offline Hari Ini</small>
            <strong>
              {loadingTransactions || transactionError
                ? "-"
                : rupiah(totalPendapatan)}
            </strong>
          </div>
        </div>
      </div>

      {/* PESAN ERROR */}
      {tenantError && (
        <div className="offline-panel" role="alert" style={{ padding: 16 }}>
          <p>Gagal memuat tenant: {tenantError}</p>
          <button type="button" onClick={refreshTenants}>Coba Lagi</button>
        </div>
      )}

      {transactionError && (
        <div className="offline-panel" role="alert" style={{ padding: 16 }}>
          <p>Gagal memuat transaksi: {transactionError}</p>
          <button type="button" onClick={refreshTransactions}>
            <RefreshCw size={14} /> Coba Lagi
          </button>
        </div>
      )}

      {/* PENCARIAN KODE TRANSAKSI */}
      <section className="offline-panel">
        <div className="offline-panel-heading">
          <div>
            <h2>Cari Transaksi Pelanggan</h2>
            <p>Masukkan kode yang diberikan Tenant kepada pelanggan.</p>
          </div>
          <span className="offline-cash-badge">
            <Banknote size={15} /> Pembayaran Tunai
          </span>
        </div>

        <form className="offline-search-form" onSubmit={handleSearch}>
          <label htmlFor="offline-tenant">Pilih Tenant</label>
          <select
            id="offline-tenant"
            value={tenantId}
            disabled={tenantLoading || searching}
            onChange={(event) => {
              setTenantId(event.target.value);
              setFound(null);
              setMessage("");
            }}
          >
            <option value="">
              {tenantLoading ? "Memuat tenant..." : "Pilih tenant"}
            </option>
            {tenants.map((tenant) => (
              <option key={tenant.id} value={String(tenant.id)}>
                {tenant.nama}
              </option>
            ))}
          </select>

          <label htmlFor="offline-code">Kode Transaksi</label>
          <input
            id="offline-code"
            type="text"
            placeholder="Contoh: 1.15"
            value={kode}
            disabled={searching}
            onChange={(event) => {
              setKode(event.target.value);
              setFound(null);
              setMessage("");
            }}
          />

          <div className="offline-search-footer">
            <span>
              <Info size={15} />
              Kode transaksi dibuat oleh Tenant setelah mencatat pesanan.
            </span>
            <button
              type="submit"
              disabled={searching || tenantLoading || Boolean(tenantError)}
            >
              <Search size={16} />
              {searching ? "Mencari..." : "Cari Transaksi"}
            </button>
          </div>
          {message && (
            <p className="offline-message" role="status">{message}</p>
          )}
        </form>

        {/* DETAIL PESANAN DARI TENANT */}
        {found && (
          <div className="offline-result">
            <div className="offline-result-header">
              <h3>Detail Transaksi</h3>
              <button
                type="button"
                aria-label="Tutup detail"
                onClick={() => {
                  setFound(null);
                  setUangDiterima("");
                  setMessage("");
                }}
              >
                <X size={17} />
              </button>
            </div>

            <div className="offline-result-grid">
              <div>
                <span>Kode Transaksi</span>
                <strong>{found.kode}</strong>
              </div>
              <div>
                <span>Nama Tenant</span>
                <strong>{found.tenant ?? "-"}</strong>
              </div>
              <div>
                <span>Total Tagihan</span>
                <strong>{rupiah(found.total)}</strong>
              </div>
              <div>
                <span>Status Pembayaran</span>
                <strong>{found.status}</strong>
              </div>
            </div>

            <div className="offline-table-scroll">
              <table className="offline-table">
                <thead>
                  <tr>
                    <th>Menu / Prasmanan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  {found.items.length === 0 ? (
                    <tr>
                      <td colSpan={4} className="offline-empty">
                        Rincian pesanan belum tersedia dari server.
                      </td>
                    </tr>
                  ) : (
                    found.items.map((item, index) => (
                      <tr key={item.id ?? index}>
                        <td>{item.nama_item}</td>
                        <td>{rupiah(item.harga)}</td>
                        <td>{item.jumlah}</td>
                        <td>{rupiah(item.subtotal)}</td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>

            <div style={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
              padding: "14px 2px",
              borderTop: "1px solid #F1E8DE",
              fontSize: 13
            }}>
              <strong>Total Pembayaran</strong>
              <strong style={{ fontSize: 17, color: "#650916" }}>
                {rupiah(totalTagihan)}
              </strong>
            </div>

            {found.status === "Menunggu Pembayaran" ? (
              <>
                <div style={{ display: "grid", gap: 10, marginTop: 10 }}>
                  <label htmlFor="uang-diterima">Uang Diterima (Cash)</label>
                  <input
                    id="uang-diterima"
                    type="number"
                    min={totalTagihan}
                    step="1"
                    placeholder="Masukkan nominal uang pelanggan"
                    value={uangDiterima}
                    disabled={confirming}
                    onChange={(event) => setUangDiterima(event.target.value)}
                  />
                  <div style={{
                    padding: "13px 15px",
                    borderRadius: 9,
                    background: "#FFF2CB",
                    display: "flex",
                    justifyContent: "space-between"
                  }}>
                    <strong>Kembalian</strong>
                    <strong>{rupiah(kembalian)}</strong>
                  </div>
                </div>
                <button
                  type="button"
                  className="offline-confirm-btn"
                  disabled={confirming || !uangCukup || found.items.length === 0}
                  onClick={handleConfirm}
                >
                  <CheckCircle2 size={16} />
                  {confirming ? "Menyimpan..." : "Konfirmasi Pembayaran Tunai"}
                </button>
              </>
            ) : (
              <p className="offline-paid">
                <CheckCircle2 size={16} />
                {found.status === "Lunas"
                  ? "Pembayaran sudah lunas. Tenant dapat melihat perubahan status."
                  : `Status transaksi: ${found.status}`}
              </p>
            )}
            {message && <p className="offline-message" role="status">{message}</p>}
          </div>
        )}
      </section>

      {/* DAFTAR TRANSAKSI OFFLINE */}
      <section className="offline-panel">
        <div className="offline-panel-heading">
          <div>
            <h2>Daftar Transaksi Offline</h2>
            <p>Pantau transaksi yang tercatat dari seluruh Tenant.</p>
          </div>
          <div className="offline-filter">
            <label htmlFor="offline-filter-tenant">Lihat Transaksi</label>
            <select
              id="offline-filter-tenant"
              value={filterTenant}
              disabled={tenantLoading}
              onChange={(event) => setFilterTenant(event.target.value)}
            >
              <option value="">Semua Tenant</option>
              {tenants.map((tenant) => (
                <option key={tenant.id} value={String(tenant.id)}>
                  {tenant.nama}
                </option>
              ))}
            </select>
          </div>
        </div>

        <div style={{ padding: "10px 18px", fontSize: 11, color: "#77717D" }}>
          {loadingTransactions ? "Memuat transaksi..." : transactionError
            ? "Data transaksi belum tersedia."
            : `${menungguPembayaran} transaksi menunggu pembayaran`}
        </div>

        <div className="offline-table-scroll">
          <table className="offline-table">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Tenant</th>
                <th>Waktu</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              {loadingTransactions || transactionError ? (
                <tr>
                  <td colSpan={6} className="offline-empty">
                    {loadingTransactions ? "Memuat transaksi..." : "Gagal memuat transaksi."}
                  </td>
                </tr>
              ) : filteredTransactions.length === 0 ? (
                <tr>
                  <td colSpan={6} className="offline-empty">
                    Belum ada transaksi offline dari Tenant.
                  </td>
                </tr>
              ) : (
                filteredTransactions.map((item) => (
                  <tr key={item.id}>
                    <td><strong>{item.kode}</strong></td>
                    <td>{item.tenant ?? "-"}</td>
                    <td>{item.waktu ?? "-"}</td>
                    <td>{rupiah(item.total)}</td>
                    <td>
                      <span className={`offline-status ${
                        item.status === "Lunas" ? "paid" : "pending"
                      }`}>
                        {item.status}
                      </span>
                    </td>
                    <td>
                      <button
                        type="button"
                        className="small-btn"
                        onClick={() => {
                          setTenantId(String(item.tenant_id));
                          setKode(item.kode);
                          setFound(null);
                          setMessage("Kode sudah dipilih. Klik Cari Transaksi untuk melihat detail.");
                          setUangDiterima("");
                        }}
                      >
                        Pilih Kode
                      </button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </section>
    </div>
  );
}

export default TransaksiOffline;
