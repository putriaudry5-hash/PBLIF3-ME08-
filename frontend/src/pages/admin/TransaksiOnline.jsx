
import { useEffect, useState } from "react";
import {
  Store, Wallet, CreditCard, ChevronDown, RefreshCw
} from "lucide-react";
import { useTenants } from "../../context/useTenants.js";
import { apiRequest } from "../../services/api.js";
import "../../styles/TransaksiOnline.css";

const rupiah = (value) =>
  `Rp ${Number(value || 0).toLocaleString("id-ID")}`;

const tanggalLokal = () => {
  const date = new Date();
  const tahun = date.getFullYear();
  const bulan = String(date.getMonth() + 1).padStart(2, "0");
  const hari = String(date.getDate()).padStart(2, "0");
  return `${tahun}-${bulan}-${hari}`;
};

function TransaksiOnline() {
  const { tenants = [], loading: tenantLoading, error: tenantError } = useTenants();
  const [openTenant, setOpenTenant] = useState(null);
  const [transactions, setTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [refreshKey, setRefreshKey] = useState(0);

  useEffect(() => {
    let active = true;

    const fetchTransactions = async () => {
      try {
        const result = await apiRequest("/admin/online-transactions");
        if (!active) return;

        const data = Array.isArray(result)
          ? result
          : Array.isArray(result?.data)
            ? result.data
            : [];

        setTransactions(data);
        setError("");
      } catch (err) {
        if (!active) return;
        setError(err.message || "Gagal mengambil transaksi online.");
        setTransactions([]);
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchTransactions();
    return () => { active = false; };
  }, [refreshKey]);

  const refreshTransactions = () => {
    setLoading(true);
    setRefreshKey((prev) => prev + 1);
  };

  const today = tanggalLokal();

  const todayTransactions = transactions.filter(
    (item) => String(item.created_at || "").slice(0, 10) === today
  );

  const getTransaksiTenant = (tenant) =>
    todayTransactions.filter(
      (item) =>
        String(item.tenant_id ?? item.tenantId) === String(tenant.id)
    );

  const totalPendapatan = todayTransactions
    .filter((item) => item.pembayaran === "Lunas")
    .reduce((sum, item) => sum + Number(item.total || 0), 0);

  return (
    <div className="online-page">
      <div className="online-heading">
        <h1>Transaksi Online</h1>
        <p>Pantau transaksi online setiap tenant secara langsung.</p>
      </div>

      <div className="online-stats">
        <div className="online-stat-card">
          <span className="online-stat-icon">
            <CreditCard size={21} />
          </span>
          <div>
            <small>Transaksi Online Hari Ini</small>
            <strong>
              {loading || error ? "-" : `${todayTransactions.length} Transaksi`}
            </strong>
          </div>
        </div>
        <div className="online-stat-card">
          <span className="online-stat-icon">
            <Wallet size={21} />
          </span>
          <div>
            <small>Pendapatan Online Hari Ini</small>
            <strong>{loading || error ? "-" : rupiah(totalPendapatan)}</strong>
          </div>
        </div>
      </div>

      {error && (
        <div className="online-tenant-card" style={{ padding: 18 }} role="alert">
          <p>Gagal memuat transaksi online: {error}</p>
          <button type="button" onClick={refreshTransactions}>
            <RefreshCw size={15} /> Coba Lagi
          </button>
        </div>
      )}

      {tenantError && (
        <div className="online-tenant-card" style={{ padding: 18 }} role="alert">
          <p>Gagal memuat tenant: {tenantError}</p>
        </div>
      )}

      <div className="online-tenant-list">
        {tenants.map((tenant) => {
          const isOpen = openTenant === tenant.id;
          const transaksiTenant = getTransaksiTenant(tenant);
          const pendapatan = transaksiTenant
            .filter((item) => item.pembayaran === "Lunas")
            .reduce((sum, item) => sum + Number(item.total || 0), 0);

          return (
            <section
              className={`online-tenant-card ${isOpen ? "open" : ""}`}
              key={tenant.id}
            >
              <button
                type="button"
                className="online-tenant-toggle"
                aria-expanded={isOpen}
                onClick={() => setOpenTenant(isOpen ? null : tenant.id)}
              >
                <span className="online-tenant-icon">
                  <Store size={19} />
                </span>
                <span className="online-tenant-info">
                  <strong>{tenant.nama}</strong>
                  <small>
                    {loading || error
                      ? "Data transaksi belum tersedia"
                      : `${transaksiTenant.length} transaksi online hari ini`}
                  </small>
                </span>
                <span className="online-tenant-revenue">
                  <small>Pendapatan</small>
                  <strong>
                    {loading || error ? "-" : rupiah(pendapatan)}
                  </strong>
                </span>
                <ChevronDown
                  size={19}
                  className={`online-chevron ${isOpen ? "rotated" : ""}`}
                />
              </button>

              {isOpen && (
                <div className="online-table-scroll">
                  <table className="online-table">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Waktu</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      {loading || error ? (
                        <tr>
                          <td colSpan={8} className="online-empty">
                            {loading ? "Memuat transaksi..." : "Data transaksi belum tersedia."}
                          </td>
                        </tr>
                      ) : transaksiTenant.length === 0 ? (
                        <tr>
                          <td colSpan={8} className="online-empty">
                            Belum ada transaksi online untuk tenant ini hari ini.
                          </td>
                        </tr>
                      ) : (
                        transaksiTenant.map((item) => (
                          <tr key={item.id}>
                            <td>{item.kode}</td>
                            <td>{item.pelanggan || "-"}</td>
                            <td>{item.waktu || "-"}</td>
                            <td>{rupiah(item.total)}</td>
                            <td>{item.metode || "-"}</td>
                            <td>
                              <span className="online-status">
                                {item.pembayaran || "Menunggu"}
                              </span>
                            </td>
                            <td>{item.status || "-"}</td>
                            <td>-</td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              )}
            </section>
          );
        })}

        {!tenantLoading && tenants.length === 0 && !tenantError && (
          <div className="online-tenant-card">
            <div className="online-empty">Belum ada tenant yang terdaftar.</div>
          </div>
        )}
      </div>
    </div>
  );
}

export default TransaksiOnline;
