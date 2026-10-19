import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useTenants } from "../../context/useTenants.js";
import { apiRequest } from "../../services/api.js";
import {
  Store, ReceiptText, Clock3, Wallet,
  ChevronRight, FileX2, ChartNoAxesCombined,
  RefreshCw
} from "lucide-react";

const rupiah = (value) =>
  `Rp ${Number(value || 0).toLocaleString("id-ID")}`;

const hariIni = () => {
  const parts = new Intl.DateTimeFormat("en-GB", {
    timeZone: "Asia/Jakarta",
    year: "numeric",
    month: "2-digit",
    day: "2-digit"
  }).formatToParts(new Date());

  const get = (key) =>
    parts.find((part) => part.type === key)?.value;

  return `${get("year")}-${get("month")}-${get("day")}`;
};

function Dashboard() {
  const navigate = useNavigate();

  const {
    tenants = [],
    loading: tenantLoading,
    error: tenantError,
    refreshTenants
  } = useTenants();

  const [transactions, setTransactions] = useState([]);
  const [pendingTransactions, setPendingTransactions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [refreshKey, setRefreshKey] = useState(0);

  useEffect(() => {
    let active = true;

    const loadDashboard = async () => {
      try {
        const [reportResult, offlineResult] = await Promise.all([
          apiRequest("/admin/reports"),
          apiRequest("/admin/offline-transactions")
        ]);

        if (!active) return;

        const reportData = Array.isArray(reportResult)
          ? reportResult
          : Array.isArray(reportResult?.data)
            ? reportResult.data
            : [];

        const offlineData = Array.isArray(offlineResult)
          ? offlineResult
          : Array.isArray(offlineResult?.data)
            ? offlineResult.data
            : [];

        setTransactions(reportData);
        setPendingTransactions(
          offlineData.filter(
            (item) => item.status === "Menunggu Pembayaran"
          )
        );
        setError("");
      } catch (err) {
        if (!active) return;

        setError(err.message || "Gagal memuat data Dashboard.");
        setTransactions([]);
        setPendingTransactions([]);
      } finally {
        if (active) setLoading(false);
      }
    };

    loadDashboard();

    return () => {
      active = false;
    };
  }, [refreshKey]);

  const refreshDashboard = () => {
    setLoading(true);
    setRefreshKey((prev) => prev + 1);
  };

  const totalTenantAktif = tenants.filter(
    (tenant) =>
      String(tenant.status || "").toLowerCase() === "aktif"
  ).length;

  const tanggalSekarang = hariIni();

  const transaksiHariIni = transactions.filter(
    (item) => String(item.tanggal || "").slice(0, 10) === tanggalSekarang
  );

  const transaksiLunasHariIni = transaksiHariIni.filter(
    (item) => item.pembayaran === "Lunas"
  );

  const pendapatanHariIni = transaksiLunasHariIni.reduce(
    (total, item) => total + Number(item.total || 0),
    0
  );

  const transaksiTerbaru = [...transactions]
    .sort((a, b) => Number(b.id) - Number(a.id))
    .slice(0, 5);

  const transaksiPending = [...pendingTransactions]
    .sort((a, b) => Number(b.id) - Number(a.id))
    .slice(0, 5);

  const stats = [
    {
      title: "Total Tenant Aktif",
      value: tenantLoading || tenantError ? "-" : totalTenantAktif,
      description: "Tenant dengan status aktif",
      icon: Store,
      color: "yellow"
    },
    {
      title: "Transaksi Hari Ini",
      value: loading || error ? "-" : transaksiHariIni.length,
      description: "Online & Offline",
      icon: ReceiptText,
      color: "red"
    },
    {
      title: "Menunggu Pembayaran",
      value: loading || error ? "-" : pendingTransactions.length,
      description: "Transaksi offline",
      icon: Clock3,
      color: "yellow"
    },
    {
      title: "Pendapatan Hari Ini",
      value: loading || error ? "-" : rupiah(pendapatanHariIni),
      description: "Seluruh tenant",
      icon: Wallet,
      color: "green"
    }
  ];

  const shortcuts = [
    {
      title: "Transaksi Offline",
      description: "Cari kode & konfirmasi cash",
      path: "/admin/transaksi-offline",
      icon: ReceiptText
    },
    {
      title: "Data Tenant",
      description: "Kelola akun tenant",
      path: "/admin/tenant",
      icon: Store
    },
    {
      title: "Laporan",
      description: "Lihat rekap seluruh tenant",
      path: "/admin/laporan",
      icon: ChartNoAxesCombined
    }
  ];

  return (
    <div className="dashboard-page">
      <div className="page-heading">
        <h1>Dashboard</h1>
        <p>Ringkasan aktivitas KantinKita hari ini.</p>
      </div>

      <div className="stats-grid">
        {stats.map(({
          title, value, description, icon: Icon, color
        }) => (
          <div className="stat-card" key={title}>
            <div className={`stat-icon ${color}`}>
              <Icon size={23} strokeWidth={1.8} />
            </div>
            <div>
              <span>{title}</span>
              <h2>{value}</h2>
              <small>{description}</small>
            </div>
          </div>
        ))}
      </div>

      {tenantError && (
        <div role="alert" style={{
          marginBottom: 20,
          padding: "14px 18px",
          border: "1px solid #f1c6c6",
          borderRadius: 12,
          background: "#fff7f7",
          color: "#a51b1b"
        }}>
          <span>Gagal memuat data tenant: {tenantError}</span>
          <button
            type="button"
            onClick={() => refreshTenants()}
            style={{
              marginLeft: 12,
              cursor: "pointer",
              fontWeight: "bold",
              color: "#a51b1b"
            }}
          >
            Coba Lagi
          </button>
        </div>
      )}

      {error && (
        <div role="alert" style={{
          marginBottom: 20,
          padding: "14px 18px",
          border: "1px solid #f1c6c6",
          borderRadius: 12,
          background: "#fff7f7",
          color: "#a51b1b"
        }}>
          <span>Gagal memuat transaksi: {error}</span>
          <button
            type="button"
            onClick={refreshDashboard}
            style={{
              marginLeft: 12,
              cursor: "pointer",
              fontWeight: "bold",
              color: "#a51b1b"
            }}
          >
            <RefreshCw size={14} /> Coba Lagi
          </button>
        </div>
      )}

      <div className="dashboard-columns">
        <section className="admin-panel">
          <div className="panel-header">
            <div>
              <h2>Transaksi Terbaru</h2>
              <p>Transaksi online dan offline terbaru.</p>
            </div>
            <button
              type="button"
              className="text-action"
              onClick={() => navigate("/admin/riwayat-transaksi")}
            >
              Lihat Semua →
            </button>
          </div>

          <div className="table-scroll">
            <table className="admin-table">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Waktu</th>
                  <th>Tenant</th>
                  <th>Total</th>
                  <th>Jenis</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {loading || error ? (
                  <tr>
                    <td colSpan={7} style={{
                      textAlign: "center",
                      padding: "35px 15px",
                      color: "#8b8290"
                    }}>
                      {loading
                        ? "Memuat data transaksi..."
                        : "Data transaksi belum tersedia."}
                    </td>
                  </tr>
                ) : transaksiTerbaru.length === 0 ? (
                  <tr>
                    <td colSpan={7} style={{
                      textAlign: "center",
                      padding: "35px 15px",
                      color: "#8b8290"
                    }}>
                      Belum ada transaksi yang tercatat.
                    </td>
                  </tr>
                ) : (
                  transaksiTerbaru.map((item) => (
                    <tr key={item.id}>
                      <td><strong>{item.kode}</strong></td>
                      <td>
                        {item.tanggal || "-"}
                        {item.waktu ? ` ${item.waktu}` : ""}
                      </td>
                      <td>{item.tenant || "-"}</td>
                      <td>{rupiah(item.total)}</td>
                      <td>
                        <span className="pill pill-orange">
                          {item.jenis}
                        </span>
                      </td>
                      <td>
                        <span className={`pill ${
                          item.pembayaran === "Lunas"
                            ? "pill-green"
                            : "pill-yellow"
                        }`}>
                          {item.pembayaran || item.status}
                        </span>
                      </td>
                      <td>
                        <button
                          type="button"
                          className="small-btn"
                          onClick={() =>
                            navigate("/admin/riwayat-transaksi")
                          }
                        >
                          Lihat
                        </button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </section>

        <section className="admin-panel shortcuts-panel">
          <div className="panel-header">
            <div>
              <h2>Akses Cepat</h2>
              <p>Menu yang sering digunakan.</p>
            </div>
          </div>

          <div className="shortcut-list">
            {shortcuts.map(({
              title, description, path, icon: Icon
            }) => (
              <button
                type="button"
                className="shortcut-item"
                key={title}
                onClick={() => navigate(path)}
              >
                <span className="shortcut-icon">
                  <Icon size={20} strokeWidth={1.8} />
                </span>
                <span className="shortcut-text">
                  <strong>{title}</strong>
                  <small>{description}</small>
                </span>
                <ChevronRight size={17} strokeWidth={1.8} />
              </button>
            ))}
          </div>
        </section>
      </div>

      <section className="admin-panel pending-panel">
        <div className="panel-header">
          <div>
            <h2>Transaksi Offline Menunggu Pembayaran</h2>
            <p>
              Pesanan yang sudah dicatat Tenant dan belum
              dikonfirmasi pembayaran oleh Kasir.
            </p>
          </div>
          <span className="pill pill-yellow">
            {loading || error
              ? "-"
              : pendingTransactions.length} Menunggu
          </span>
        </div>

        {loading || error ? (
          <div className="pending-empty">
            <FileX2 size={25} strokeWidth={1.8} />
            <div>
              <strong>
                {loading
                  ? "Memuat transaksi offline..."
                  : "Transaksi belum dapat ditampilkan."}
              </strong>
            </div>
          </div>
        ) : transaksiPending.length === 0 ? (
          <div className="pending-empty">
            <FileX2 size={25} strokeWidth={1.8} />
            <div>
              <strong>
                Tidak ada transaksi menunggu pembayaran
              </strong>
              <p>
                Pesanan offline yang belum dibayar akan muncul di sini.
              </p>
            </div>
          </div>
        ) : (
          <div className="table-scroll">
            <table className="admin-table">
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
                {transaksiPending.map((item) => (
                  <tr key={item.id}>
                    <td><strong>{item.kode}</strong></td>
                    <td>{item.tenant}</td>
                    <td>{item.waktu || "-"}</td>
                    <td>{rupiah(item.total)}</td>
                    <td>
                      <span className="pill pill-yellow">
                        {item.status}
                      </span>
                    </td>
                    <td>
                      <button
                        type="button"
                        className="small-btn"
                        onClick={() =>
                          navigate("/admin/transaksi-offline")
                        }
                      >
                        Lihat
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </section>
    </div>
  );
}

export default Dashboard;
