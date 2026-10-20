import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { TenantProvider } from "./context/TenantContext.jsx";
import ProtectedRoute from "./components/ProtectedRoute";

// LOGIN
import LoginInternal from "./pages/auth/LoginInternal";

// LAYOUT
import AdminLayout from "./layouts/AdminLayout";
import TenantLayout from "./layouts/TenantLayout";

// HALAMAN ADMIN
import Dashboard from "./pages/admin/Dashboard";
import DataTenant from "./pages/admin/DataTenant";
import DataPelanggan from "./pages/admin/DataPelanggan";
import TransaksiOffline from "./pages/admin/TransaksiOffline";
import TransaksiOnline from "./pages/admin/TransaksiOnline";
import RiwayatTransaksi from "./pages/admin/RiwayatTransaksi";
import Laporan from "./pages/admin/Laporan";
import Pengaturan from "./pages/admin/Pengaturan";

// HALAMAN TENANT
import DashboardTenant from "./pages/tenant/DashboardTenant";
import KelolaMenuTenant from "./pages/tenant/KelolaMenuTenant";
import PesananOnlineTenant from "./pages/tenant/PesananOnlineTenant";
import PesananOfflineTenant from "./pages/tenant/PesananOfflineTenant";
import RiwayatTransaksiTenant from "./pages/tenant/RiwayatTransaksiTenant";
import LaporanTenant from "./pages/tenant/LaporanTenant";
import PengaturanTenant from "./pages/tenant/PengaturanTenant";

function App() {
  return (
    <BrowserRouter>
      <TenantProvider>
        <Routes>
          {/* HALAMAN AWAL DAN LOGIN */}
          <Route path="/" element={<Navigate to="/login/internal" replace />} />
          <Route path="/login/internal" element={<LoginInternal />} />

          {/* ADMIN - WAJIB LOGIN */}
          <Route element={<ProtectedRoute allowedRoles={["admin"]} />}>
            <Route path="/admin" element={<AdminLayout />}>
              <Route index element={<Navigate to="dashboard" replace />} />
              <Route path="dashboard" element={<Dashboard />} />
              <Route path="tenant" element={<DataTenant />} />
              <Route path="pelanggan" element={<DataPelanggan />} />
              <Route path="transaksi-offline" element={<TransaksiOffline />} />
              <Route path="transaksi-online" element={<TransaksiOnline />} />
              <Route path="riwayat-transaksi" element={<RiwayatTransaksi />} />
              <Route path="laporan" element={<Laporan />} />
              <Route path="pengaturan" element={<Pengaturan />} />
            </Route>
          </Route>

          {/* TENANT - PRATINJAU FRONTEND */}
          <Route path="/tenant" element={<TenantLayout />}>
            <Route index element={<Navigate to="dashboard" replace />} />
            <Route path="dashboard" element={<DashboardTenant />} />
            <Route path="menu" element={<KelolaMenuTenant />} />
            <Route path="pesanan-online" element={<PesananOnlineTenant />} />
            <Route path="transaksi-offline" element={<PesananOfflineTenant />} />
            <Route path="riwayat-transaksi" element={<RiwayatTransaksiTenant />} />
            <Route path="laporan" element={<LaporanTenant />} />
            <Route path="pengaturan" element={<PengaturanTenant />} />
          </Route>

          {/* HALAMAN TIDAK DITEMUKAN */}
          <Route path="*" element={<Navigate to="/login/internal" replace />} />
        </Routes>
      </TenantProvider>
    </BrowserRouter>
  );
}

export default App;
