import { useEffect, useState } from "react";
import {
  CreditCard, Banknote, ArrowRight, ArrowLeft,
  History, Store, ChevronDown, RefreshCw
} from "lucide-react";
import { useTenants } from "../../context/useTenants.js";
import { apiRequest } from "../../services/api.js";
import "../../styles/RiwayatTransaksi.css";

const rupiah = (value) =>
  `Rp ${Number(value || 0).toLocaleString("id-ID")}`;

function RiwayatTransaksi() {
  const { tenants = [], loading: tenantLoading, error: tenantError } = useTenants();
  const [jenis, setJenis] = useState(null);
  const [tenantTerbuka, setTenantTerbuka] = useState(null);
  const [riwayat, setRiwayat] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [refreshKey, setRefreshKey] = useState(0);

  useEffect(() => {
    if (!jenis) return;

    let active = true;

    const fetchRiwayat = async () => {
      try {
        const result = await apiRequest(
          `/admin/transaction-history?jenis=${jenis}`
        );
        if (!active) return;

        const data = Array.isArray(result)
          ? result
          : Array.isArray(result?.data)
            ? result.data
            : [];

        setRiwayat(data);
        setError("");
      } catch (err) {
        if (!active) return;
        setError(err.message || "Gagal memuat riwayat transaksi.");
        setRiwayat([]);
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchRiwayat();
    return () => { active = false; };
  }, [jenis, refreshKey]);

  const bukaRiwayat = (pilihan) => {
    setJenis(pilihan);
    setTenantTerbuka(null);
    setRiwayat([]);
    setError("");
    setLoading(true);
  };

  const kembali = () => {
    setJenis(null);
    setTenantTerbuka(null);
    setRiwayat([]);
    setError("");
  };

  const toggleTenant = (id) => {
    setTenantTerbuka((prev) => prev === id ? null : id);
  };

  const refreshRiwayat = () => {
    setLoading(true);
    setRefreshKey((prev) => prev + 1);
  };

  const transaksiTenant = (tenant) =>
    riwayat.filter(
      (item) => String(item.tenant_id) === String(tenant.id)
    );

  return (
    <div className="riwayat-page">
      <div className="riwayat-heading">
        <h1>Riwayat Transaksi</h1>
        <p>Pilih jenis riwayat transaksi yang ingin dilihat.</p>
      </div>

      {!jenis ? (
        <div className="riwayat-options">
          <div className="riwayat-option">
            <span className="riwayat-option-icon online">
              <CreditCard size={22} />
            </span>
            <div className="riwayat-option-content">
              <h2>Riwayat Transaksi Online</h2>
              <p>
                Lihat transaksi pelanggan yang melakukan pemesanan
                dan pembayaran melalui website.
              </p>
              <button type="button" onClick={() => bukaRiwayat("online")}>
                Lihat Riwayat Online <ArrowRight size={15} />
              </button>
            </div>
          </div>

          <div className="riwayat-option">
            <span className="riwayat-option-icon offline">
              <Banknote size={22} />
            </span>
            <div className="riwayat-option-content">
              <h2>Riwayat Transaksi Offline</h2>
              <p>
                Lihat transaksi pelanggan yang melakukan pembayaran
                tunai melalui Kasir Utama.
              </p>
              <button type="button" onClick={() => bukaRiwayat("offline")}>
                Lihat Riwayat Offline <ArrowRight size={15} />
              </button>
            </div>
          </div>
        </div>
      ) : (
        <section className="riwayat-panel">
          <div className="riwayat-panel-heading">
            <div>
              <button type="button" className="riwayat-back" onClick={kembali}>
                <ArrowLeft size={15} /> Kembali
              </button>
              <h2>
                Riwayat Transaksi {jenis === "online" ? "Online" : "Offline"}
              </h2>
              <p>Daftar transaksi {jenis} berdasarkan tenant KantinKita.</p>
            </div>
            <span className="riwayat-count">
              <History size={15} /> {loading || error ? "-" : riwayat.length} Transaksi
            </span>
          </div>

          {error && (
            <div role="alert" style={{ padding: "12px 18px", color: "#CD1C18" }}>
              <p>Gagal memuat riwayat: {error}</p>
              <button type="button" onClick={refreshRiwayat}>
                <RefreshCw size={15} /> Coba Lagi
              </button>
            </div>
          )}

          {tenantError && (
            <div role="alert" style={{ padding: "12px 18px", color: "#CD1C18" }}>
              Gagal memuat daftar tenant: {tenantError}
            </div>
          )}

          <div className="riwayat-tenant-list">
            {tenants.map((tenant) => {
              const transaksi = transaksiTenant(tenant);
              const terbuka = tenantTerbuka === tenant.id;

              return (
                <div className="riwayat-tenant-card" key={tenant.id}>
                  <button
                    type="button"
                    className={`riwayat-tenant-toggle ${terbuka ? "is-open" : ""}`}
                    onClick={() => toggleTenant(tenant.id)}
                    aria-expanded={terbuka}
                  >
                    <span className="riwayat-tenant-icon">
                      <Store size={20} strokeWidth={1.8} />
                    </span>
                    <span className="riwayat-tenant-info">
                      <strong>{tenant.nama}</strong>
                      <small>
                        {loading || error
                          ? "Memuat riwayat..."
                          : `${transaksi.length} transaksi ${jenis}`}
                      </small>
                    </span>
                    <span className="riwayat-tenant-total">
                      {loading || error ? "-" : `${transaksi.length} Transaksi`}
                    </span>
                    <ChevronDown
                      size={19}
                      className={`riwayat-chevron ${terbuka ? "rotate" : ""}`}
                    />
                  </button>

                  {terbuka && (
                    <div className="riwayat-tenant-content">
                      <div className="riwayat-table-scroll">
                        <table className="riwayat-table">
                          <thead>
                            <tr>
                              <th>Kode</th>
                              <th>Tenant</th>
                              <th>Pelanggan</th>
                              <th>Tanggal</th>
                              <th>Total</th>
                              <th>Pembayaran</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            {loading || error ? (
                              <tr>
                                <td colSpan={7} className="riwayat-empty">
                                  {loading ? "Memuat riwayat..." : "Data belum tersedia."}
                                </td>
                              </tr>
                            ) : transaksi.length === 0 ? (
                              <tr>
                                <td colSpan={7} className="riwayat-empty">
                                  Belum ada riwayat transaksi {jenis} untuk {tenant.nama}.
                                </td>
                              </tr>
                            ) : (
                              transaksi.map((item) => (
                                <tr key={item.id}>
                                  <td>{item.kode}</td>
                                  <td>{item.tenant ?? tenant.nama}</td>
                                  <td>{item.pelanggan ?? "-"}</td>
                                  <td>{item.tanggal ?? "-"}</td>
                                  <td>{rupiah(item.total)}</td>
                                  <td>{item.pembayaran ?? "-"}</td>
                                  <td>{item.status ?? "-"}</td>
                                </tr>
                              ))
                            )}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  )}
                </div>
              );
            })}

            {!tenantLoading && tenants.length === 0 && !tenantError && (
              <div className="riwayat-no-tenant">
                Belum ada tenant yang terdaftar.
              </div>
            )}
          </div>
        </section>
      )}
    </div>
  );
}

export default RiwayatTransaksi;
