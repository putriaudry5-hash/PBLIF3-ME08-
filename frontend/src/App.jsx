
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { TenantProvider } from "./context/TenantContext.jsx";
import ProtectedRoute from "./components/ProtectedRoute";

// LOGIN
import LoginInternal from "./pages/auth/LoginInternal";

// LAYOUT ADMIN
import AdminLayout from "./layouts/AdminLayout";

// HALAMAN ADMIN
import Dashboard from "./pages/admin/Dashboard";
import DataTenant from "./pages/admin/DataTenant";
import DataPelanggan from "./pages/admin/DataPelanggan";
import TransaksiOffline from "./pages/admin/TransaksiOffline";
import TransaksiOnline from "./pages/admin/TransaksiOnline";
import RiwayatTransaksi from "./pages/admin/RiwayatTransaksi";
import Laporan from "./pages/admin/Laporan";
import Pengaturan from "./pages/admin/Pengaturan";

function App() {
  return (
    <BrowserRouter>
      <TenantProvider>
        <Routes>
          {/* HALAMAN AWAL */}
          <Route
            path="/"
            element={<Navigate to="/login/internal" replace />}
          />

          {/* LOGIN ADMIN DAN TENANT */}
          <Route
            path="/login/internal"
            element={<LoginInternal />}
          />

          {/* HALAMAN ADMIN - WAJIB LOGIN */}
          <Route
            element={<ProtectedRoute allowedRoles={["admin"]} />}
          >
            <Route path="/admin" element={<AdminLayout />}>
              <Route
                index
                element={<Navigate to="dashboard" replace />}
              />

              <Route path="dashboard" element={<Dashboard />} />

              <Route path="tenant" element={<DataTenant />} />

              <Route path="pelanggan" element={<DataPelanggan />} />

              <Route
                path="transaksi-offline"
                element={<TransaksiOffline />}
              />

              <Route
                path="transaksi-online"
                element={<TransaksiOnline />}
              />

              <Route
                path="riwayat-transaksi"
                element={<RiwayatTransaksi />}
              />

              <Route path="laporan" element={<Laporan />} />

              <Route path="pengaturan" element={<Pengaturan />} />
            </Route>
          </Route>

          {/* HALAMAN TIDAK DITEMUKAN */}
          <Route
            path="*"
            element={<Navigate to="/login/internal" replace />}
          />
        </Routes>
      </TenantProvider>
    </BrowserRouter>
  );
}

export default App;
