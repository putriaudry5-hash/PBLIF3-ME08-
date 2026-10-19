
import { useState } from "react";
import {
  Bell,
  ChevronDown,
  LogOut,
  Settings,
} from "lucide-react";

import { Outlet, useNavigate } from "react-router-dom";
import AdminSidebar from "../components/admin/AdminSidebar";
import "../styles/Admin.css";

/* =====================================
   DEKORASI KANAN ATAS
===================================== */

function TopDecoration() {
  return (
    <svg
      className="admin-top-decoration"
      viewBox="0 0 340 195"
      fill="none"
      aria-hidden="true"
      preserveAspectRatio="xMaxYMin meet"
    >
      {/* LENGKUNG CREAM MUDA */}

      <path
        d="
          M120 0
          H340
          V160
          C293 129 248 116 214 97
          C172 75 140 36 120 0
          Z
        "
        fill="#FFF3C9"
        opacity="0.45"
      />

      {/* KUNING LEMON */}

      <path
        d="
          M175 0
          H340
          V88
          C302 70 268 58 239 46
          C207 32 189 13 175 0
          Z
        "
        fill="#FFE566"
      />

      {/* AKSEN MAROON POJOK */}

      <path
        d="
          M317 0
          H340
          V25
          C333 15 324 6 317 0
          Z
        "
        fill="#CD1C18"
      />

      {/* TITIK KUNING */}

      <circle
        cx="270"
        cy="93"
        r="2.5"
        fill="#E9AF25"
      />

      {/* TITIK MERAH */}

      <circle
        cx="301"
        cy="125"
        r="10"
        fill="#CD1C18"
      />
    </svg>
  );
}

/* =====================================
   GELOMBANG KIRI BAWAH
===================================== */

function BottomDecoration() {
  return (
    <svg
      className="admin-bottom-decoration"
      viewBox="0 0 420 185"
      fill="none"
      aria-hidden="true"
      preserveAspectRatio="xMinYMax meet"
    >
      <path
        d="
          M0 25
          C55 9 105 45 145 77
          C187 112 214 147 258 165
          C319 190 370 179 420 185
        "
        stroke="#EFA6A0"
        strokeWidth="1.5"
      />

      <path
        d="
          M0 50
          C42 42 87 59 129 87
          C170 115 198 143 238 154
          C304 174 359 170 420 185
          H0 Z
        "
        fill="#FFE566"
      />

      <path
        d="
          M0 65
          C46 55 85 76 128 106
          C167 134 193 145 220 146
          C250 147 261 135 284 141
          C326 153 365 175 420 185
          H0 Z
        "
        fill="#FFF0A2"
      />

      <path
        d="
          M0 112
          C47 90 91 98 134 119
          C176 140 203 158 241 169
          C299 186 355 181 420 185
          H0 Z
        "
        fill="#890016"
      />

      <path
        d="
          M0 148
          C52 126 98 118 143 127
          C187 135 220 156 263 169
          C312 184 371 181 420 185
          H0 Z
        "
        fill="#CD1C18"
      />

      <path
        d="
          M0 139
          C53 119 103 119 148 129
          C200 143 227 162 276 176
          C326 188 370 182 420 185
        "
        stroke="#F06F69"
        strokeWidth="1.5"
      />
    </svg>
  );
}

/* =====================================
   LAYOUT ADMIN
===================================== */

function AdminLayout() {
  const [openProfile, setOpenProfile] = useState(false);
  const navigate = useNavigate();

  return (
    <div className="admin-shell">
      <AdminSidebar />

      <div className="admin-main">
        <TopDecoration />

        <header className="admin-topbar">
          <div className="admin-topbar-space" />

          <div className="admin-topbar-actions">
            <button
              className="topbar-bell"
              title="Notifikasi"
              aria-label="Notifikasi"
              type="button"
            >
              <Bell size={20} />
              <span className="notification-dot" />
            </button>

            <div className="profile-wrapper">
              <button
                type="button"
                className="admin-profile"
                onClick={() => setOpenProfile(!openProfile)}
                aria-expanded={openProfile}
              >
                <span className="admin-avatar">A</span>

                <span className="admin-profile-text">
                  <strong>Admin Utama</strong>
                  <small>Kasir Utama / Admin</small>
                </span>

                <ChevronDown size={16} />
              </button>

              {openProfile && (
                <div className="profile-dropdown">
                  <strong>Admin Utama</strong>
                  <small>admin@kantinkita.com</small>

                  <button
                    type="button"
                    onClick={() => {
                      setOpenProfile(false);
                      navigate("/admin/pengaturan");
                    }}
                  >
                    <Settings size={16} />
                    Pengaturan
                  </button>

                  <button
                    type="button"
                    onClick={() => {
                      setOpenProfile(false);
                      navigate("/login/internal");
                    }}
                  >
                    <LogOut size={16} />
                    Keluar
                  </button>
                </div>
              )}
            </div>
          </div>
        </header>

        <main className="admin-content">
          <Outlet />
        </main>
      </div>

      <BottomDecoration />
    </div>
  );
}

export default AdminLayout;
