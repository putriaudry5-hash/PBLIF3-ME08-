import {
  LayoutDashboard, Store, Users, ReceiptText,
  CreditCard, History, ChartNoAxesCombined,
  Settings
} from "lucide-react";
import { NavLink } from "react-router-dom";

const menus = [
  { label: "Dashboard", path: "/admin/dashboard", icon: LayoutDashboard },
  { label: "Data Tenant", path: "/admin/tenant", icon: Store },
  { label: "Data Pelanggan", path: "/admin/pelanggan", icon: Users },
  { label: "Transaksi Offline", path: "/admin/transaksi-offline", icon: ReceiptText },
  { label: "Transaksi Online", path: "/admin/transaksi-online", icon: CreditCard },
  { label: "Riwayat Transaksi", path: "/admin/riwayat-transaksi", icon: History },
  { label: "Laporan", path: "/admin/laporan", icon: ChartNoAxesCombined },
  { label: "Pengaturan", path: "/admin/pengaturan", icon: Settings },
];

function AdminSidebar() {
  return (
    <aside className="admin-sidebar">
      <div className="sidebar-brand">
        <span className="sidebar-logo">K</span>
        <div>
          <strong>Kantin<span>Kita</span></strong>
          <small>Kasir Utama / Admin</small>
        </div>
      </div>

      <nav className="sidebar-menu">
        {menus.map(({ label, path, icon: Icon }) => (
          <NavLink
            key={path}
            to={path}
            className={({ isActive }) =>
              `sidebar-link ${isActive ? "active" : ""}`
            }
          >
            <Icon size={18} strokeWidth={1.8} />
            <span>{label}</span>
          </NavLink>
        ))}
      </nav>
    </aside>
  );
}

export default AdminSidebar;