import {
  BrowserRouter,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";

import { GoogleOAuthProvider } from "@react-oauth/google";
import { GOOGLE_CLIENT_ID } from "./config";

import { TenantProvider } from "./context/TenantContext.jsx";
import ProtectedRoute from "./components/ProtectedRoute";

// LOGIN
import LoginInternal from "./pages/auth/LoginInternal";
import LoginPelanggan from "./pages/auth/LoginPelanggan";

// LAYOUT ADMIN DAN TENANT
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

// HALAMAN PELANGGAN
import DashboardPelanggan from "./pages/pelanggan/DashboardPelanggan";
import ProfilPelanggan from "./pages/pelanggan/ProfilPelanggan";
import MenuTenant from "./pages/pelanggan/MenuTenant";
import Keranjang from "./pages/pelanggan/Keranjang";
import Checkout from "./pages/pelanggan/Checkout";
import HasilPembayaran from "./pages/pelanggan/HasilPembayaran";
import DetailPesanan from "./pages/pelanggan/DetailPesanan";
import PesananSaya from "./pages/pelanggan/PesananSaya";
import RiwayatPesanan from "./pages/pelanggan/RiwayatPesanan";

function App() {
  return (
    <GoogleOAuthProvider clientId={GOOGLE_CLIENT_ID}>
      <BrowserRouter>
        <TenantProvider>
          <Routes>
            {/* HALAMAN AWAL */}
            <Route
              path="/"
              element={<Navigate to="/login/pelanggan" replace />}
            />

            {/* LOGIN */}
            <Route
              path="/login/internal"
              element={<LoginInternal />}
            />

            <Route
              path="/login/pelanggan"
              element={<LoginPelanggan />}
            />

            {/* =========================
                HALAMAN PELANGGAN
            ========================= */}

            <Route
              path="/beranda"
              element={<DashboardPelanggan />}
            />

            <Route
              path="/profil"
              element={<ProfilPelanggan />}
            />

            <Route
              path="/pesanan-saya"
              element={<PesananSaya />}
            />

            <Route
              path="/riwayat-pesanan"
              element={<RiwayatPesanan />}
            />

            <Route
              path="/keranjang"
              element={<Keranjang />}
            />

            <Route
              path="/checkout"
              element={<Checkout />}
            />

            <Route
              path="/hasil-pembayaran"
              element={<HasilPembayaran />}
            />

            <Route
              path="/detail-pesanan"
              element={<DetailPesanan />}
            />

            {/* MENU TENANT UNTUK PELANGGAN */}
            <Route
              path="/tenant/:id/menu"
              element={<MenuTenant />}
            />

            {/* =========================
                HALAMAN TENANT
                SEMENTARA TANPA LOGIN
            ========================= */}

            <Route
              path="/tenant"
              element={<TenantLayout />}
            >
              <Route
                index
                element={
                  <Navigate
                    to="/tenant/dashboard"
                    replace
                  />
                }
              />

              <Route
                path="dashboard"
                element={<DashboardTenant />}
              />

              <Route
                path="menu"
                element={<KelolaMenuTenant />}
              />

              <Route
                path="pesanan-online"
                element={<PesananOnlineTenant />}
              />

              <Route
                path="transaksi-offline"
                element={<PesananOfflineTenant />}
              />

              <Route
                path="riwayat-transaksi"
                element={<RiwayatTransaksiTenant />}
              />

              <Route
                path="laporan"
                element={<LaporanTenant />}
              />

              <Route
                path="pengaturan"
                element={<PengaturanTenant />}
              />
            </Route>

            {/* =========================
                HALAMAN ADMIN
                WAJIB LOGIN
            ========================= */}

            <Route
              element={
                <ProtectedRoute allowedRoles={["admin"]} />
              }
            >
              <Route
                path="/admin"
                element={<AdminLayout />}
              >
                <Route
                  index
                  element={
                    <Navigate to="dashboard" replace />
                  }
                />

                <Route
                  path="dashboard"
                  element={<Dashboard />}
                />

                <Route
                  path="tenant"
                  element={<DataTenant />}
                />

                <Route
                  path="pelanggan"
                  element={<DataPelanggan />}
                />

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

                <Route
                  path="laporan"
                  element={<Laporan />}
                />

                <Route
                  path="pengaturan"
                  element={<Pengaturan />}
                />
              </Route>
            </Route>

            {/* HALAMAN TIDAK DITEMUKAN */}
            <Route
              path="*"
              element={
                <Navigate
                  to="/login/pelanggan"
                  replace
                />
              }
            />
          </Routes>
        </TenantProvider>
      </BrowserRouter>
    </GoogleOAuthProvider>
  );
}

export default App;