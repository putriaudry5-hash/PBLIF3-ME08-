import { useNavigate } from "react-router-dom";
import { Construction, ArrowLeft } from "lucide-react";

function TenantPlaceholder({ title }) {
  const navigate = useNavigate();

  return (
    <div className="tenant-placeholder-page">
      <div className="tenant-page-heading">
        <div>
          <h1>{title}</h1>
          <p>Halaman Tenant KantinKita.</p>
        </div>
      </div>

      <div className="tenant-placeholder-card">
        <Construction size={38} strokeWidth={1.4} />
        <h2>Tampilan {title}</h2>
        <p>
          Halaman ini akan kita buat setelah Dashboard Tenant selesai.
        </p>
        <button
          className="tenant-primary-btn"
          type="button"
          onClick={() => navigate("/tenant/dashboard")}
        >
          <ArrowLeft size={16} />
          Kembali ke Dashboard
        </button>
      </div>
    </div>
  );
}

export default TenantPlaceholder;
