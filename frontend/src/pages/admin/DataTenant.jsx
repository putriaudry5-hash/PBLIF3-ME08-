
import { useState } from "react";
import {
  Store,
  Plus,
  Pencil,
  X,
  Save,
  Eye,
  RefreshCw
} from "lucide-react";

import { useTenants } from "../../context/useTenants.js";
import "../../styles/DataTenant.css";

const emptyForm = {
  nama: "",
  pj: "",
  email: "",
  telepon: "",
  jenis: "Menu Satuan",
  lokasi: ""
};

const jenisOptions = [
  "Menu Satuan",
  "Prasmanan",
  "Menu Satuan & Prasmanan"
];

function DataTenant() {
  const {
    tenants,
    loading,
    error: apiError,
    refreshTenants,
    tambahTenant,
    editTenant,
    ubahStatusTenant
  } = useTenants();

  const [modal, setModal] = useState(null);
  const [selected, setSelected] = useState(null);
  const [form, setForm] = useState({ ...emptyForm });
  const [error, setError] = useState("");
  const [saving, setSaving] = useState(false);

  const openAdd = () => {
    setSelected(null);
    setForm({ ...emptyForm });
    setError("");
    setModal("add");
  };

  const openDetail = (tenant) => {
    setSelected(tenant);
    setError("");
    setModal("detail");
  };

  const openEdit = (tenant) => {
    setSelected(tenant);
    setForm({
      nama: tenant.nama || "",
      pj: tenant.pj || "",
      email: tenant.email || "",
      telepon: tenant.telepon || "",
      jenis: tenant.jenis || "Menu Satuan",
      lokasi: tenant.lokasi || ""
    });
    setError("");
    setModal("edit");
  };

  const closeModal = () => {
    if (saving) return;

    setModal(null);
    setSelected(null);
    setError("");
  };

  const handleChange = (e) => {
    const { name, value } = e.target;

    setForm((prev) => ({
      ...prev,
      [name]: value
    }));

    setError("");
  };

  const handleSave = async (e) => {
    e.preventDefault();

    if (saving) return;

    const data = {
      nama: form.nama.trim(),
      pj: form.pj.trim(),
      email: form.email.trim().toLowerCase(),
      telepon: form.telepon.trim(),
      jenis: form.jenis,
      lokasi: form.lokasi.trim()
    };

    if (
      !data.nama ||
      !data.pj ||
      !data.email ||
      !data.telepon ||
      !data.jenis ||
      !data.lokasi
    ) {
      setError("Semua kolom wajib diisi.");
      return;
    }

    if (!/^08[0-9]{8,11}$/.test(data.telepon)) {
      setError(
        "Nomor HP harus diawali 08 dan berisi 10-13 digit."
      );
      return;
    }

    const emailUsed = tenants.some((tenant) => {
      return (
        tenant.email?.toLowerCase() === data.email &&
        (modal === "add" ||
          String(tenant.id) !== String(selected?.id))
      );
    });

    if (emailUsed) {
      setError("Email sudah digunakan oleh tenant lain.");
      return;
    }

    try {
      setSaving(true);
      setError("");

      if (modal === "add") {
        await tambahTenant(data);
      } else if (modal === "edit" && selected) {
        await editTenant(selected.id, data);
      }

      setModal(null);
      setSelected(null);
    } catch (err) {
      setError(
        err.message || "Gagal menyimpan data tenant."
      );
    } finally {
      setSaving(false);
    }
  };

  const toggleStatus = async (tenant) => {
    if (saving) return;

    const action =
      tenant.status === "Aktif" ? "Nonaktifkan" : "Aktifkan";

    const confirmed = window.confirm(
      `${action} ${tenant.nama}?`
    );

    if (!confirmed) return;

    try {
      setSaving(true);
      await ubahStatusTenant(tenant.id);
    } catch (err) {
      window.alert(
        err.message || "Gagal mengubah status tenant."
      );
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="tenant-page">
      {/* JUDUL HALAMAN */}
      <div className="tenant-page-heading">
        <h1>Data Tenant</h1>
        <p>Kelola tenant yang terdaftar pada KantinKita.</p>
      </div>

      {/* RINGKASAN TENANT */}
      <div className="tenant-summary">
        <div className="tenant-summary-left">
          <div className="tenant-summary-icon">
            <Store size={22} strokeWidth={1.8} />
          </div>

          <div>
            <span>Total Tenant</span>
            <strong>
              {tenants.length} Tenant Terdaftar
            </strong>
          </div>
        </div>

        <button
          type="button"
          className="tenant-add-btn"
          onClick={openAdd}
          disabled={saving}
        >
          <Plus size={17} />
          Tambah Tenant
        </button>
      </div>

      {/* DAFTAR TENANT */}
      <section className="tenant-panel">
        <div className="tenant-panel-heading">
          <div>
            <h2>Daftar Tenant</h2>
            <p>
              Informasi tenant yang dikelola oleh
              Kasir Utama/Admin.
            </p>
          </div>
        </div>

        {apiError && (
          <div
            role="alert"
            style={{
              padding: "12px 18px",
              color: "#CD1C18",
              fontSize: "12px"
            }}
          >
            <p style={{ marginBottom: 8 }}>
              Gagal mengambil data tenant: {apiError}
            </p>

            <button
              type="button"
              className="tenant-cancel"
              onClick={refreshTenants}
            >
              <RefreshCw size={14} />
              Coba Lagi
            </button>
          </div>
        )}

        <div className="tenant-table-scroll">
          <table className="tenant-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Tenant</th>
                <th>Jenis</th>
                <th>Penanggung Jawab</th>
                <th>Kontak</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              {loading ? (
                <tr>
                  <td colSpan={7}>
                    Sedang memuat data tenant...
                  </td>
                </tr>
              ) : tenants.length === 0 ? (
                <tr>
                  <td colSpan={7}>
                    Belum ada tenant terdaftar.
                  </td>
                </tr>
              ) : (
                tenants.map((tenant, index) => (
                  <tr key={tenant.id}>
                    <td>{index + 1}</td>

                    <td>
                      <div className="tenant-name">
                        <span className="tenant-initial">
                          TE
                        </span>

                        <strong>{tenant.nama}</strong>
                      </div>
                    </td>

                    <td>
                      <span className="tenant-type">
                        {tenant.jenis}
                      </span>
                    </td>

                    <td>{tenant.pj}</td>

                    <td>
                      <div className="tenant-contact">
                        <span>{tenant.email}</span>
                        <small>{tenant.telepon}</small>
                      </div>
                    </td>

                    <td>
                      <span
                        className={`tenant-status ${
                          tenant.status === "Aktif"
                            ? "is-active"
                            : "is-inactive"
                        }`}
                      >
                        {tenant.status}
                      </span>
                    </td>

                    <td>
                      <div className="tenant-actions">
                        <button
                          type="button"
                          className="tenant-btn detail"
                          onClick={() => openDetail(tenant)}
                        >
                          <Eye size={13} />
                          Detail
                        </button>

                        <button
                          type="button"
                          className="tenant-btn edit"
                          onClick={() => openEdit(tenant)}
                          disabled={saving}
                        >
                          <Pencil size={13} />
                          Edit
                        </button>

                        <button
                          type="button"
                          disabled={saving}
                          className={`tenant-btn ${
                            tenant.status === "Aktif"
                              ? "deactivate"
                              : "activate"
                          }`}
                          onClick={() => toggleStatus(tenant)}
                        >
                          {tenant.status === "Aktif"
                            ? "Nonaktifkan"
                            : "Aktifkan"}
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </section>

      {/* MODAL TAMBAH, EDIT, DETAIL */}
      {modal && (
        <div
          className="tenant-modal-overlay"
          onMouseDown={(e) => {
            if (e.target === e.currentTarget) {
              closeModal();
            }
          }}
        >
          <div
            className="tenant-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="tenant-modal-title"
          >
            <div className="tenant-modal-header">
              <div>
                <h2 id="tenant-modal-title">
                  {modal === "add"
                    ? "Tambah Tenant"
                    : modal === "edit"
                    ? "Edit Tenant"
                    : "Detail Tenant"}
                </h2>

                <p>
                  {modal === "add"
                    ? "Tambahkan informasi tenant baru."
                    : modal === "edit"
                    ? "Ubah informasi tenant."
                    : "Informasi lengkap tenant."}
                </p>
              </div>

              <button
                type="button"
                onClick={closeModal}
                disabled={saving}
                aria-label="Tutup"
              >
                <X size={19} />
              </button>
            </div>

            {modal === "detail" && selected ? (
              <div className="tenant-detail">
                {[
                  ["Nama Tenant", selected.nama],
                  ["Penanggung Jawab", selected.pj],
                  ["Email", selected.email],
                  ["Nomor Telepon", selected.telepon],
                  ["Jenis Tenant", selected.jenis],
                  ["Lokasi / Nomor Kios", selected.lokasi],
                  ["Status", selected.status]
                ].map(([label, value]) => (
                  <div key={label}>
                    <span>{label}</span>
                    <strong>{value || "-"}</strong>
                  </div>
                ))}

                <button
                  type="button"
                  className="tenant-modal-primary"
                  onClick={() => openEdit(selected)}
                >
                  <Pencil size={15} />
                  Edit Tenant
                </button>
              </div>
            ) : (
              <form
                className="tenant-form"
                onSubmit={handleSave}
              >
                <label>
                  Nama Tenant
                  <input
                    name="nama"
                    value={form.nama}
                    onChange={handleChange}
                    placeholder="Contoh: Dapur Bu Sari"
                    maxLength={150}
                    required
                  />
                </label>

                <label>
                  Penanggung Jawab
                  <input
                    name="pj"
                    value={form.pj}
                    onChange={handleChange}
                    placeholder="Nama pemilik atau penjaga tenant"
                    maxLength={150}
                    required
                  />
                </label>

                <label>
                  Email
                  <input
                    type="email"
                    name="email"
                    value={form.email}
                    onChange={handleChange}
                    placeholder="tenant@kantinkita.com"
                    maxLength={150}
                    required
                  />
                </label>

                <label>
                  Nomor Telepon
                  <input
                    type="tel"
                    name="telepon"
                    value={form.telepon}
                    onChange={handleChange}
                    placeholder="08xxxxxxxxxx"
                    inputMode="numeric"
                    maxLength={13}
                    required
                  />
                </label>

                <label>
                  Jenis Tenant
                  <select
                    name="jenis"
                    value={form.jenis}
                    onChange={handleChange}
                    required
                  >
                    {jenisOptions.map((jenis) => (
                      <option key={jenis} value={jenis}>
                        {jenis}
                      </option>
                    ))}
                  </select>
                </label>

                <label>
                  Lokasi / Nomor Kios
                  <input
                    name="lokasi"
                    value={form.lokasi}
                    onChange={handleChange}
                    placeholder="Contoh: Kios 03"
                    maxLength={100}
                    required
                  />
                </label>

                {error && (
                  <p
                    className="tenant-form-error"
                    role="alert"
                  >
                    {error}
                  </p>
                )}

                <div className="tenant-form-actions">
                  <button
                    type="button"
                    className="tenant-cancel"
                    onClick={closeModal}
                    disabled={saving}
                  >
                    Batal
                  </button>

                  <button
                    type="submit"
                    className="tenant-modal-primary"
                    disabled={saving}
                  >
                    <Save size={15} />
                    {saving
                      ? "Menyimpan..."
                      : modal === "add"
                      ? "Tambah Tenant"
                      : "Simpan Perubahan"}
                  </button>
                </div>
              </form>
            )}
          </div>
        </div>
      )}
    </div>
  );
}

export default DataTenant;
