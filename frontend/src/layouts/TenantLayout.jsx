
import { useState } from "react";
import { NavLink, Outlet, useNavigate } from "react-router-dom";
import {
  House,
  UtensilsCrossed,
  ClipboardList,
  ReceiptText,
  History,
  ChartNoAxesColumnIncreasing,
  Settings,
  ChevronDown,
  Menu,
  X,
  LogOut,
  Bell
} from "lucide-react";

import "../styles/Tenant/Tenant.css";

/* =========================================
   MENU NAVIGASI TENANT
========================================= */

const menuTenant = [
  {
    label: "Dashboard",
    path: "/tenant/dashboard",
    icon: House
  },
  {
    label: "Kelola Menu",
    path: "/tenant/menu",
    icon: UtensilsCrossed
  },
  {
    label: "Pesanan Online",
    path: "/tenant/pesanan-online",
    icon: ClipboardList
  },
  {
    label: "Pesanan Offline",
    path: "/tenant/transaksi-offline",
    icon: ReceiptText
  },
  {
    label: "Riwayat Transaksi",
    path: "/tenant/riwayat-transaksi",
    icon: History
  },
  {
    label: "Laporan Penjualan",
    path: "/tenant/laporan",
    icon: ChartNoAxesColumnIncreasing
  },
  {
    label: "Pengaturan",
    path: "/tenant/pengaturan",
    icon: Settings
  }
];

/* =========================================
   LAYOUT TENANT
========================================= */

export default function TenantLayout() {
  const navigate = useNavigate();

  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [profileOpen, setProfileOpen] = useState(false);
  const [notificationOpen, setNotificationOpen] = useState(false);

  // Data contoh sementara untuk frontend
  const namaTenant = "Ayam Geprek Bu Rina";
  const jumlahPesananBaru = 3;

  const tutupSemua = () => {
    setSidebarOpen(false);
    setProfileOpen(false);
    setNotificationOpen(false);
  };

  const pindahHalaman = (path) => {
    tutupSemua();
    navigate(path);
  };

  // Sementara kembali ke halaman login
  const kembaliLogin = () => {
    tutupSemua();
    navigate("/login/internal");
  };

  return (
    <div className="tenant-shell">

      {/* OVERLAY SIDEBAR MOBILE */}
      {sidebarOpen && (
        <button
          type="button"
          className="tenant-overlay"
          aria-label="Tutup sidebar"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* =====================================
          SIDEBAR TENANT
      ===================================== */}

      <aside
        className={`tenant-sidebar ${
          sidebarOpen ? "show" : ""
        }`}
      >

        {/* LOGO KANTINKITA */}
        <div className="tenant-brand">
          <div className="tenant-brand-logo">
            K
          </div>

          <div className="tenant-brand-text">
            <strong>
              Kantin<span>Kita</span>
            </strong>
            <small>Tenant</small>
          </div>

          <button
            type="button"
            className="tenant-sidebar-close"
            aria-label="Tutup sidebar"
            onClick={() => setSidebarOpen(false)}
          >
            <X size={18} />
          </button>
        </div>

        {/* MENU NAVIGASI */}
        <nav
          className="tenant-nav"
          aria-label="Navigasi Tenant"
        >
          {menuTenant.map(
            ({ label, path, icon: Icon }) => (
              <NavLink
                key={path}
                to={path}
                onClick={tutupSemua}
                className={({ isActive }) =>
                  `tenant-nav-link ${
                    isActive ? "active" : ""
                  }`
                }
              >
                <Icon size={17} strokeWidth={1.9} />

                <span className="tenant-nav-text">
                  {label}
                </span>

                {/* BADGE PESANAN ONLINE BARU */}
                {path === "/tenant/pesanan-online" &&
                  jumlahPesananBaru > 0 && (
                    <span className="tenant-nav-count">
                      {jumlahPesananBaru}
                    </span>
                  )}
              </NavLink>
            )
          )}
        </nav>

        {/* =====================================
            CORAK GELOMBANG SIDEBAR
            TETAP SEPERTI SEBELUMNYA
        ===================================== */}

        <div
          className="tenant-sidebar-wave"
          aria-hidden="true"
        >
          <svg
            viewBox="0 0 226 170"
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            {/* GARIS KUNING TIPIS */}
            <path
              d="M-2 12 C38 8 69 35 103 62 C145 96 183 111 228 119"
              fill="none"
              stroke="#FFE566"
              strokeWidth="1.8"
            />

            {/* GELOMBANG KUNING */}
            <path
              d="M-2 34 C35 30 73 55 110 83 C152 115 190 127 228 135 L228 172 L-2 172Z"
              fill="#FFE566"
            />

            {/* GELOMBANG KREM */}
            <path
              d="M-2 57 C38 49 75 76 113 103 C153 131 191 143 228 147 L228 172 L-2 172Z"
              fill="#FFF0A3"
            />

            {/* GELOMBANG MERAH */}
            <path
              d="M-2 101 C42 83 80 106 119 127 C159 149 193 155 228 159 L228 172 L-2 172Z"
              fill="#E52F29"
            />

            {/* GELOMBANG MERAH BAWAH */}
            <path
              d="M-2 139 C45 116 84 130 123 145 C164 163 199 165 228 168 L228 172 L-2 172Z"
              fill="#CD1C18"
            />

            {/* DASAR SIDEBAR */}
            <path
              d="M-2 169 C57 140 96 150 136 159 C172 166 202 169 228 171 L228 172 L-2 172Z"
              fill="#650916"
            />
          </svg>
        </div>

        {/* TOMBOL KELUAR SIDEBAR TETAP DIHAPUS */}

      </aside>

      {/* =====================================
          AREA UTAMA TENANT
      ===================================== */}

      <div className="tenant-main">

        {/* HEADER */}
        <header className="tenant-topbar">

          {/* MENU MOBILE */}
          <button
            type="button"
            className="tenant-mobile-menu"
            aria-label="Buka sidebar"
            onClick={() => setSidebarOpen(true)}
          >
            <Menu size={22} />
          </button>

          <div className="tenant-topbar-spacer" />

          <div className="tenant-topbar-actions">

            {/* NOTIFIKASI */}
            <div className="tenant-notification-wrapper">
              <button
                type="button"
                className="tenant-notification"
                aria-label="Notifikasi"
                aria-expanded={notificationOpen}
                onClick={() => {
                  setNotificationOpen((prev) => !prev);
                  setProfileOpen(false);
                }}
              >
                <Bell size={20} />

                {jumlahPesananBaru > 0 && (
                  <span className="tenant-notification-dot" />
                )}
              </button>

              {notificationOpen && (
                <div className="tenant-notification-dropdown">
                  <strong>Notifikasi</strong>

                  <p>
                    Ada {jumlahPesananBaru} pesanan baru
                    (data contoh).
                  </p>

                  <button
                    type="button"
                    onClick={() =>
                      pindahHalaman(
                        "/tenant/pesanan-online"
                      )
                    }
                  >
                    Lihat Pesanan
                  </button>
                </div>
              )}
            </div>

            {/* PROFIL TENANT */}
            <div className="tenant-profile-wrapper">

              <button
                type="button"
                className="tenant-profile"
                aria-expanded={profileOpen}
                onClick={() => {
                  setProfileOpen((prev) => !prev);
                  setNotificationOpen(false);
                }}
              >
                <span className="tenant-avatar">
                  R
                </span>

                <span className="tenant-profile-text">
                  <strong>{namaTenant}</strong>
                  <small>Tenant</small>
                </span>

                <ChevronDown size={15} />
              </button>

              {/* DROPDOWN PROFIL */}
              {profileOpen && (
                <div className="tenant-profile-dropdown">

                  <strong>{namaTenant}</strong>
                  <small>Akun Tenant</small>

                  <button
                    type="button"
                    onClick={() =>
                      pindahHalaman(
                        "/tenant/pengaturan"
                      )
                    }
                  >
                    <Settings size={15} />
                    Pengaturan
                  </button>

                  <button
                    type="button"
                    onClick={kembaliLogin}
                  >
                    <LogOut size={15} />
                    Keluar
                  </button>

                </div>
              )}
            </div>

          </div>
        </header>

        {/* KONTEN HALAMAN */}
        <main className="tenant-content">
          <Outlet />
        </main>

      </div>
    </div>
  );
}
