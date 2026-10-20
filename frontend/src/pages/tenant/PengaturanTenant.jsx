
import { useState } from "react";
import {
  Store,
  UserRound,
  Phone,
  Mail,
  FileText,
  Clock3,
  ShieldCheck,
  Save,
  CircleCheck,
  X,
  Info
} from "lucide-react";

import "../../styles/Tenant/PengaturanTenant.css";

// DATA AWAL PENGATURAN TENANT
const STORAGE_KEY = "kantinkita-pengaturan-tenant-preview";

const pengaturanAwal = {
  namaTenant: "Ayam Geprek Bu Rina",
  namaPemilik: "Bu Rina",
  email: "tenant@example.com",
  telepon: "081234567890",
  deskripsi: "Menyediakan aneka makanan rumahan, ayam geprek, dan prasmanan.",
  jamBuka: "08:00",
  jamTutup: "17:00",
  pesananOnline: true
};

// MEMBACA DATA DARI LOCAL STORAGE
function bacaPengaturan() {
  try {
    const tersimpan = localStorage.getItem(STORAGE_KEY);
    if (!tersimpan) return { ...pengaturanAwal };

    const hasil = JSON.parse(tersimpan);
    if (!hasil || typeof hasil !== "object" || Array.isArray(hasil)) {
      return { ...pengaturanAwal };
    }

    // Hanya membaca field yang masih digunakan.
    // Data QRIS lama tidak ikut dimasukkan lagi.
    return {
      namaTenant: hasil.namaTenant ?? pengaturanAwal.namaTenant,
      namaPemilik: hasil.namaPemilik ?? pengaturanAwal.namaPemilik,
      email: pengaturanAwal.email,
      telepon: hasil.telepon ?? pengaturanAwal.telepon,
      deskripsi: hasil.deskripsi ?? pengaturanAwal.deskripsi,
      jamBuka: hasil.jamBuka ?? pengaturanAwal.jamBuka,
      jamTutup: hasil.jamTutup ?? pengaturanAwal.jamTutup,
      pesananOnline:
        typeof hasil.pesananOnline === "boolean"
          ? hasil.pesananOnline
          : pengaturanAwal.pesananOnline
    };
  } catch {
    return { ...pengaturanAwal };
  }
}

export default function PengaturanTenant() {
  const [form, setForm] = useState(bacaPengaturan);
  const [tersimpan, setTersimpan] = useState(bacaPengaturan);
  const [pesan, setPesan] = useState("");
  const [error, setError] = useState("");

  const adaPerubahan =
    JSON.stringify(form) !== JSON.stringify(tersimpan);

  // MENGUBAH NILAI FORM
  function ubahForm(nama, nilai) {
    setForm((sebelumnya) => ({
      ...sebelumnya,
      [nama]: nilai
    }));

    setPesan("");
    setError("");
  }

  // SIMPAN PENGATURAN
  function simpanPengaturan(event) {
    event.preventDefault();

    if (!form.namaTenant.trim()) {
      setError("Nama tenant wajib diisi.");
      return;
    }

    if (!form.namaPemilik.trim()) {
      setError("Nama pemilik wajib diisi.");
      return;
    }

    const nomor = form.telepon.replace(/[\s-]/g, "");

    if (!/^(\+62|62|0)[0-9]{8,13}$/.test(nomor)) {
      setError("Nomor telepon tidak valid.");
      return;
    }

    if (
      form.jamBuka &&
      form.jamTutup &&
      form.jamTutup <= form.jamBuka
    ) {
      setError("Jam tutup harus lebih akhir dari jam buka.");
      return;
    }

    const dataBaru = {
      namaTenant: form.namaTenant.trim(),
      namaPemilik: form.namaPemilik.trim(),
      email: pengaturanAwal.email,
      telepon: form.telepon.trim(),
      deskripsi: form.deskripsi.trim(),
      jamBuka: form.jamBuka,
      jamTutup: form.jamTutup,
      pesananOnline: form.pesananOnline
    };

    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(dataBaru));

      setForm(dataBaru);
      setTersimpan(dataBaru);
      setError("");
      setPesan("Pengaturan berhasil disimpan pada browser ini.");
    } catch {
      setError(
        "Pengaturan gagal disimpan. Periksa penyimpanan browser."
      );
    }
  }

  // BATALKAN PERUBAHAN
  function batalkanPerubahan() {
    setForm({ ...tersimpan });
    setError("");
    setPesan("");
  }

  return (
    <div className="pg-page">
      {/* HEADER */}
      <div className="pg-heading">
        <h1>Pengaturan</h1>
        <p>Kelola informasi tenant, operasional, dan pengaturan akun.</p>
      </div>

      {/* NOTIFIKASI BERHASIL */}
      {pesan && (
        <div className="pg-notice" role="status">
          <CircleCheck size={17} />
          <span>{pesan}</span>
          <button
            type="button"
            aria-label="Tutup pemberitahuan"
            onClick={() => setPesan("")}
          >
            <X size={16} />
          </button>
        </div>
      )}

      {/* NOTIFIKASI ERROR */}
      {error && (
        <div className="pg-error" role="alert">
          <Info size={17} />
          <span>{error}</span>
          <button
            type="button"
            aria-label="Tutup pesan error"
            onClick={() => setError("")}
          >
            <X size={16} />
          </button>
        </div>
      )}

      <form onSubmit={simpanPengaturan}>
        {/* =====================================
            INFORMASI TENANT
        ===================================== */}
        <section className="pg-panel">
          <div className="pg-panel-header">
            <span className="pg-section-icon">
              <Store size={19} />
            </span>
            <div>
              <h2>Informasi Tenant</h2>
              <p>Informasi dasar usaha yang ditampilkan kepada pelanggan.</p>
            </div>
          </div>

          <div className="pg-panel-body">
            <div className="pg-identity">
              <div className="pg-identity-avatar">
                {form.namaTenant.trim().charAt(0).toUpperCase() || "T"}
              </div>
              <div>
                <strong>{form.namaTenant || "Nama Tenant"}</strong>
                <span>Profil Usaha KantinKita</span>
              </div>
              <span className="pg-identity-label">Tenant</span>
            </div>

            <div className="pg-form-grid">
              <label className="pg-field">
                <span><Store size={14} /> Nama Tenant *</span>
                <input
                  type="text"
                  required
                  maxLength={100}
                  value={form.namaTenant}
                  onChange={(e) => ubahForm("namaTenant", e.target.value)}
                />
              </label>

              <label className="pg-field">
                <span><UserRound size={14} /> Nama Pemilik *</span>
                <input
                  type="text"
                  required
                  maxLength={100}
                  value={form.namaPemilik}
                  onChange={(e) => ubahForm("namaPemilik", e.target.value)}
                />
              </label>

              <label className="pg-field">
                <span><Phone size={14} /> Nomor Telepon *</span>
                <input
                  type="tel"
                  required
                  maxLength={20}
                  value={form.telepon}
                  placeholder="08xxxxxxxxxx"
                  onChange={(e) => ubahForm("telepon", e.target.value)}
                />
              </label>

              <label className="pg-field">
                <span><Mail size={14} /> Email Akun</span>
                <input type="email" value={form.email} disabled readOnly />
                <small>Email login dikelola melalui sistem akun.</small>
              </label>

              <label className="pg-field pg-full">
                <span><FileText size={14} /> Deskripsi Tenant</span>
                <textarea
                  rows={3}
                  maxLength={300}
                  value={form.deskripsi}
                  placeholder="Tuliskan deskripsi usaha..."
                  onChange={(e) => ubahForm("deskripsi", e.target.value)}
                />
              </label>
            </div>
          </div>
        </section>

        {/* =====================================
            PENGATURAN OPERASIONAL
        ===================================== */}
        <section className="pg-panel">
          <div className="pg-panel-header">
            <span className="pg-section-icon">
              <Clock3 size={19} />
            </span>
            <div>
              <h2>Pengaturan Operasional</h2>
              <p>Atur jam layanan dan penerimaan pesanan online.</p>
            </div>
          </div>

          <div className="pg-panel-body">
            <div className="pg-form-grid">
              <label className="pg-field">
                <span>Jam Buka</span>
                <input
                  type="time"
                  value={form.jamBuka}
                  onChange={(e) => ubahForm("jamBuka", e.target.value)}
                />
              </label>

              <label className="pg-field">
                <span>Jam Tutup</span>
                <input
                  type="time"
                  value={form.jamTutup}
                  onChange={(e) => ubahForm("jamTutup", e.target.value)}
                />
              </label>
            </div>

            <div className="pg-operational-setting">
              <div className="pg-operational-text">
                <strong>Terima Pesanan Online</strong>
                <p>
                  Jika dinonaktifkan, pelanggan tidak dapat membuat
                  pesanan online baru di tenant ini setelah pengaturan
                  terhubung ke sistem.
                </p>
                <span
                  className={`pg-operational-status ${
                    form.pesananOnline ? "aktif" : "nonaktif"
                  }`}
                >
                  {form.pesananOnline ? "Aktif" : "Nonaktif"}
                </span>
              </div>

              <label className="pg-switch">
                <input
                  type="checkbox"
                  checked={form.pesananOnline}
                  onChange={(e) =>
                    ubahForm("pesananOnline", e.target.checked)
                  }
                  aria-label="Terima Pesanan Online"
                />
                <span className="pg-switch-slider" />
              </label>
            </div>

            <div className="pg-help-text">
              <Info size={15} />
              <span>
                Pengaturan ini masih berupa pratinjau frontend.
                Dashboard dan Pesanan Online belum otomatis mengikuti
                perubahan sampai terhubung ke sistem.
              </span>
            </div>
          </div>
        </section>

        {/* =====================================
            KEAMANAN AKUN
        ===================================== */}
        <section className="pg-panel">
          <div className="pg-panel-header">
            <span className="pg-section-icon">
              <ShieldCheck size={19} />
            </span>
            <div>
              <h2>Keamanan Akun</h2>
              <p>Pengelolaan keamanan akun tenant.</p>
            </div>
          </div>

          <div className="pg-panel-body">
            <div className="pg-security">
              <div className="pg-security-icon">
                <ShieldCheck size={20} />
              </div>
              <div>
                <strong>Password Akun</strong>
                <p>
                  Untuk keamanan, perubahan password harus diproses
                  melalui backend dengan verifikasi password lama.
                </p>
              </div>
              <span className="pg-security-tag">Belum Tersedia</span>
            </div>

            <div className="pg-help-text">
              <Info size={15} />
              <span>
                Fitur ubah password akan diaktifkan setelah autentikasi
                Tenant terhubung ke Laravel. Password tidak disimpan
                di localStorage.
              </span>
            </div>
          </div>
        </section>

        {/* TOMBOL SIMPAN KANAN BAWAH */}
        <div className="pg-footer">
          {adaPerubahan && (
            <span className="pg-unsaved">
              Ada perubahan yang belum disimpan.
            </span>
          )}

          <div className="pg-footer-actions">
            <button
              type="button"
              className="pg-cancel"
              onClick={batalkanPerubahan}
              disabled={!adaPerubahan}
            >
              Batal
            </button>
            <button
              type="submit"
              className="pg-save"
              disabled={!adaPerubahan}
            >
              <Save size={16} />
              Simpan Perubahan
            </button>
          </div>
        </div>
      </form>
    </div>
  );
}
