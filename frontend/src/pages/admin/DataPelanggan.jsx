import { useEffect, useState } from "react";
import { Users, Eye, X, RefreshCw } from "lucide-react";
import { apiRequest } from "../../services/api.js";
import "../../styles/DataPelanggan.css";

function DataPelanggan() {
  const [customers, setCustomers] = useState([]);
  const [selected, setSelected] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [processingId, setProcessingId] = useState(null);
  const [refreshKey, setRefreshKey] = useState(0);

  useEffect(() => {
    let active = true;

    const fetchCustomers = async () => {
      try {
        const result = await apiRequest("/admin/customers");
        if (!active) return;

        const data = Array.isArray(result)
          ? result
          : Array.isArray(result?.data)
            ? result.data
            : [];

        setCustomers(data);
        setError("");
      } catch (err) {
        if (!active) return;
        setError(err.message || "Gagal memuat data pelanggan.");
        setCustomers([]);
      } finally {
        if (active) setLoading(false);
      }
    };

    fetchCustomers();

    return () => {
      active = false;
    };
  }, [refreshKey]);

  const refreshCustomers = () => {
    setLoading(true);
    setRefreshKey((prev) => prev + 1);
  };

  const toggleStatus = async (customer) => {
    if (processingId !== null) return;

    const isActive = customer.status === "Aktif";
    const action = isActive ? "Nonaktifkan" : "Aktifkan";

    if (!window.confirm(`${action} akun ${customer.nama}?`)) return;

    try {
      setProcessingId(customer.id);

      const result = await apiRequest(
        `/admin/customers/${customer.id}/toggle-status`,
        { method: "PATCH" }
      );

      const updated = result?.data;

      if (!updated?.id || !updated?.status) {
        throw new Error("Status pelanggan belum dapat dipastikan.");
      }

      setCustomers((prev) =>
        prev.map((item) =>
          item.id === customer.id ? { ...item, ...updated } : item
        )
      );

      setSelected((prev) =>
        prev?.id === customer.id ? { ...prev, ...updated } : prev
      );

      setError("");
    } catch (err) {
      setError(err.message || "Gagal mengubah status pelanggan.");
    } finally {
      setProcessingId(null);
    }
  };

  return (
    <div className="pelanggan-page">
      <div className="pelanggan-heading">
        <h1>Data Pelanggan</h1>
        <p>Kelola akun pelanggan yang terdaftar pada KantinKita.</p>
      </div>

      <div className="pelanggan-summary">
        <div className="pelanggan-summary-icon">
          <Users size={22} strokeWidth={1.8} />
        </div>
        <div>
          <span>Total Pelanggan</span>
          <strong>
            {loading || error
              ? "-"
              : `${customers.length} Pelanggan Terdaftar`}
          </strong>
        </div>
      </div>

      {error && (
        <div
          className="pelanggan-panel"
          role="alert"
          style={{ padding: 16, marginBottom: 16 }}
        >
          <p>{error}</p>
          <button type="button" onClick={refreshCustomers}>
            <RefreshCw size={15} /> Coba Lagi
          </button>
        </div>
      )}

      <section className="pelanggan-panel">
        <div className="pelanggan-panel-heading">
          <h2>Daftar Pelanggan</h2>
          <p>Data pelanggan yang terdaftar pada KantinKita.</p>
        </div>

        <div className="pelanggan-table-scroll">
          <table className="pelanggan-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Status</th>
                <th>Tanggal Bergabung</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              {loading || error ? (
                <tr>
                  <td colSpan={6} className="pelanggan-empty">
                    {loading
                      ? "Memuat data pelanggan..."
                      : "Data pelanggan belum dapat ditampilkan."}
                  </td>
                </tr>
              ) : customers.length === 0 ? (
                <tr>
                  <td colSpan={6} className="pelanggan-empty">
                    Belum ada pelanggan yang terdaftar.
                  </td>
                </tr>
              ) : (
                customers.map((customer, index) => {
                  const isActive = customer.status === "Aktif";
                  const isProcessing = processingId === customer.id;

                  return (
                    <tr key={customer.id}>
                      <td>{index + 1}</td>
                      <td>
                        <strong>{customer.nama}</strong>
                      </td>
                      <td>{customer.email}</td>
                      <td>
                        <span
                          className={`pelanggan-status ${
                            isActive ? "active" : "inactive"
                          }`}
                        >
                          {customer.status}
                        </span>
                      </td>
                      <td>{customer.tanggalBergabung}</td>
                      <td>
                        <div className="pelanggan-actions">
                          <button
                            type="button"
                            className="pelanggan-btn detail"
                            onClick={() => setSelected(customer)}
                          >
                            <Eye size={13} /> Detail
                          </button>

                          <button
                            type="button"
                            className={`pelanggan-btn ${
                              isActive ? "deactivate" : "activate"
                            }`}
                            onClick={() => toggleStatus(customer)}
                            disabled={processingId !== null}
                          >
                            {isProcessing
                              ? "Memproses..."
                              : isActive
                                ? "Nonaktifkan"
                                : "Aktifkan"}
                          </button>
                        </div>
                      </td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      </section>

      {selected && (
        <div
          className="pelanggan-modal-overlay"
          onMouseDown={(event) => {
            if (event.target === event.currentTarget) {
              setSelected(null);
            }
          }}
        >
          <div
            className="pelanggan-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="pelanggan-detail-title"
          >
            <div className="pelanggan-modal-header">
              <h2 id="pelanggan-detail-title">
                Detail Pelanggan
              </h2>

              <button
                type="button"
                aria-label="Tutup"
                onClick={() => setSelected(null)}
              >
                <X size={19} />
              </button>
            </div>

            <div className="pelanggan-detail">
              <div>
                <span>Nama Pelanggan</span>
                <strong>{selected.nama}</strong>
              </div>
              <div>
                <span>Email</span>
                <strong>{selected.email}</strong>
              </div>
              <div>
                <span>Status</span>
                <strong>{selected.status}</strong>
              </div>
              <div>
                <span>Tanggal Bergabung</span>
                <strong>{selected.tanggalBergabung}</strong>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default DataPelanggan;