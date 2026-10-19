import { useEffect, useState } from "react";
import {
  Store, UserRound, ShieldCheck, Save,
  Info, Eye, EyeOff, RefreshCw
} from "lucide-react";
import { apiRequest } from "../../services/api.js";
import "../../styles/Pengaturan.css";

const emptyForm = {
  namaKantin: "KantinKita",
  jamBuka: "",
  jamTutup: "",
  namaAdmin: "",
  emailAdmin: ""
};

function Pengaturan() {
  const [form, setForm] = useState(emptyForm);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");
  const [message, setMessage] = useState("");
  const [refreshKey, setRefreshKey] = useState(0);

  const [password, setPassword] = useState({
    lama: "",
    baru: "",
    konfirmasi: ""
  });

  const [showPassword, setShowPassword] = useState({
    lama: false,
    baru: false,
    konfirmasi: false
  });

  const [savingPassword, setSavingPassword] = useState(false);
  const [passwordMessage, setPasswordMessage] = useState("");
  const [passwordSuccess, setPasswordSuccess] = useState(false);

  useEffect(() => {
    let active = true;

    const loadSettings = async () => {
      try {
        const result = await apiRequest("/admin/settings");
        if (!active) return;

        if (!result?.data) {
          throw new Error("Data pengaturan tidak ditemukan.");
        }

        setForm({
          namaKantin: result.data.namaKantin ?? "KantinKita",
          jamBuka: result.data.jamBuka ?? "",
          jamTutup: result.data.jamTutup ?? "",
          namaAdmin: result.data.namaAdmin ?? "",
          emailAdmin: result.data.emailAdmin ?? ""
        });

        setError("");
      } catch (err) {
        if (active) {
          setError(err.message || "Gagal memuat pengaturan.");
        }
      } finally {
        if (active) setLoading(false);
      }
    };

    loadSettings();
    return () => { active = false; };
  }, [refreshKey]);

  const refreshSettings = () => {
    setLoading(true);
    setMessage("");
    setRefreshKey((prev) => prev + 1);
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setForm((prev) => ({ ...prev, [name]: value }));
    setMessage("");
  };

  const handleSave = async (event) => {
    event.preventDefault();
    if (saving || loading) return;

    setSaving(true);
    setMessage("");

    try {
      const result = await apiRequest("/admin/settings", {
        method: "PUT",
        body: JSON.stringify(form)
      });

      if (!result?.data) {
        throw new Error("Perubahan belum dapat dipastikan.");
      }

      setForm(result.data);
      setError("");
      setMessage("Pengaturan berhasil disimpan ke database.");
    } catch (err) {
      setMessage(err.message || "Gagal menyimpan pengaturan.");
    } finally {
      setSaving(false);
    }
  };

  const changePasswordField = (event) => {
    const { name, value } = event.target;
    setPassword((prev) => ({ ...prev, [name]: value }));
    setPasswordMessage("");
    setPasswordSuccess(false);
  };

  const handlePasswordChange = async (event) => {
    event.preventDefault();
    if (savingPassword) return;

    setPasswordMessage("");
    setPasswordSuccess(false);

    if (password.baru.length < 8) {
      setPasswordMessage("Password baru minimal 8 karakter.");
      return;
    }

    if (password.baru !== password.konfirmasi) {
      setPasswordMessage("Konfirmasi password baru tidak sesuai.");
      return;
    }

    try {
      setSavingPassword(true);

      const result = await apiRequest("/admin/change-password", {
        method: "POST",
        body: JSON.stringify({
          password_lama: password.lama,
          password_baru: password.baru,
          password_baru_confirmation: password.konfirmasi
        })
      });

      if (!result?.message) {
        throw new Error("Perubahan password belum dapat dipastikan.");
      }

      setPassword({ lama: "", baru: "", konfirmasi: "" });
      setShowPassword({
        lama: false,
        baru: false,
        konfirmasi: false
      });

      setPasswordSuccess(true);
      setPasswordMessage(result.message);
    } catch (err) {
      setPasswordMessage(err.message || "Gagal mengubah password.");
    } finally {
      setSavingPassword(false);
    }
  };

  return (
    <div className="pengaturan-page">
      <div className="pengaturan-heading">
        <h1>Pengaturan</h1>
        <p>Kelola informasi kantin dan akun Kasir Utama/Admin.</p>
      </div>

      {error && (
        <div className="pengaturan-panel"
          style={{ padding: 16, marginBottom: 18 }} role="alert">
          <p>{error}</p>
          <button type="button" onClick={refreshSettings}>
            <RefreshCw size={15} /> Coba Lagi
          </button>
        </div>
      )}

      <form className="pengaturan-main-form" onSubmit={handleSave}>
        <section className="pengaturan-panel">
          <div className="pengaturan-panel-header">
            <span className="pengaturan-section-icon yellow">
              <Store size={19} />
            </span>
            <div>
              <h2>Informasi Kantin</h2>
              <p>Atur nama dan jam operasional kantin.</p>
            </div>
          </div>

          <div className="pengaturan-panel-body">
            <div className="pengaturan-field">
              <label htmlFor="namaKantin">Nama Kantin</label>
              <input
                id="namaKantin"
                name="namaKantin"
                value={form.namaKantin}
                onChange={handleChange}
                disabled={loading || saving || !!error}
                required
              />
            </div>

            <div className="pengaturan-field-grid">
              <div className="pengaturan-field">
                <label htmlFor="jamBuka">Jam Buka</label>
                <input
                  id="jamBuka"
                  name="jamBuka"
                  type="time"
                  value={form.jamBuka}
                  onChange={handleChange}
                  disabled={loading || saving || !!error}
                />
                <small>Waktu mulai operasional kantin.</small>
              </div>

              <div className="pengaturan-field">
                <label htmlFor="jamTutup">Jam Tutup</label>
                <input
                  id="jamTutup"
                  name="jamTutup"
                  type="time"
                  value={form.jamTutup}
                  onChange={handleChange}
                  disabled={loading || saving || !!error}
                />
                <small>Waktu berakhir operasional kantin.</small>
              </div>
            </div>
          </div>
        </section>

        <section className="pengaturan-panel">
          <div className="pengaturan-panel-header">
            <span className="pengaturan-section-icon red">
              <UserRound size={19} />
            </span>
            <div>
              <h2>Profil Kasir Utama/Admin</h2>
              <p>Informasi akun Admin yang digunakan untuk login.</p>
            </div>
          </div>

          <div className="pengaturan-panel-body">
            <div className="pengaturan-field-grid">
              <div className="pengaturan-field">
                <label htmlFor="namaAdmin">Nama Admin</label>
                <input
                  id="namaAdmin"
                  name="namaAdmin"
                  value={form.namaAdmin}
                  onChange={handleChange}
                  disabled={loading || saving || !!error}
                  required
                />
              </div>

              <div className="pengaturan-field">
                <label htmlFor="emailAdmin">Email Admin</label>
                <input
                  id="emailAdmin"
                  name="emailAdmin"
                  type="email"
                  value={form.emailAdmin}
                  onChange={handleChange}
                  disabled={loading || saving || !!error}
                  required
                />
              </div>
            </div>
          </div>
        </section>

        <div className="pengaturan-save-panel">
          <span>
            <Info size={15} />
            Simpan perubahan informasi kantin dan profil Admin.
          </span>
          <button
            type="submit"
            disabled={loading || saving || !!error}
          >
            <Save size={16} />
            {saving ? "Menyimpan..." : "Simpan Pengaturan"}
          </button>
        </div>

        {message && (
          <p className="pengaturan-message" role="status">
            {message}
          </p>
        )}
      </form>

      <section className="pengaturan-panel">
        <div className="pengaturan-panel-header">
          <span className="pengaturan-section-icon green">
            <ShieldCheck size={19} />
          </span>
          <div>
            <h2>Keamanan Akun</h2>
            <p>Ubah password akun Kasir Utama/Admin.</p>
          </div>
        </div>

        <form
          className="pengaturan-panel-body pengaturan-password-form"
          onSubmit={handlePasswordChange}
        >
          {[
            {
              name: "lama",
              label: "Password Lama",
              placeholder: "Masukkan password lama"
            },
            {
              name: "baru",
              label: "Password Baru",
              placeholder: "Masukkan password baru"
            },
            {
              name: "konfirmasi",
              label: "Konfirmasi Password Baru",
              placeholder: "Ulangi password baru"
            }
          ].map((field) => (
            <div className="pengaturan-field" key={field.name}>
              <label htmlFor={`password-${field.name}`}>
                {field.label}
              </label>

              <div className="pengaturan-password-box">
                <input
                  id={`password-${field.name}`}
                  name={field.name}
                  type={
                    showPassword[field.name] ? "text" : "password"
                  }
                  value={password[field.name]}
                  onChange={changePasswordField}
                  placeholder={field.placeholder}
                  autoComplete={
                    field.name === "lama"
                      ? "current-password"
                      : "new-password"
                  }
                  disabled={savingPassword}
                  required
                />

                <button
                  type="button"
                  aria-label={
                    showPassword[field.name]
                      ? "Sembunyikan password"
                      : "Lihat password"
                  }
                  onClick={() =>
                    setShowPassword((prev) => ({
                      ...prev,
                      [field.name]: !prev[field.name]
                    }))
                  }
                >
                  {showPassword[field.name]
                    ? <EyeOff size={17} />
                    : <Eye size={17} />}
                </button>
              </div>
            </div>
          ))}

          <div className="pengaturan-password-footer">
            <small>Password baru minimal 8 karakter.</small>
            <button type="submit" disabled={savingPassword}>
              {savingPassword ? "Menyimpan..." : "Simpan Password"}
            </button>
          </div>

          {passwordMessage && (
            <p
              className="pengaturan-message"
              role={passwordSuccess ? "status" : "alert"}
            >
              {passwordMessage}
            </p>
          )}

          <p className="pengaturan-security-note">
            Gunakan password yang kuat dan jangan membagikannya
            kepada pengguna lain.
          </p>
        </form>
      </section>
    </div>
  );
}

export default Pengaturan;
