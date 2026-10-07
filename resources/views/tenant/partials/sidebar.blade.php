<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">K</div>
        <div>
            <h2>Kantin<span>Kita</span></h2>
            <small>Tenant</small>
        </div>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('tenant.dashboard') }}"
           class="{{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('tenant.menu.index') }}"
           class="{{ request()->routeIs('tenant.menu.*') ? 'active' : '' }}">
            <i data-lucide="utensils"></i>
            <span>Kelola Menu</span>
        </a>

        <a href="{{ route('tenant.transaksi.offline') }}"
           class="{{ request()->routeIs('tenant.transaksi.offline') ? 'active' : '' }}">
            <i data-lucide="receipt-text"></i>
            <span>Transaksi Offline</span>
        </a>

        <a href="{{ route('tenant.pesanan.online') }}"
           class="{{ request()->routeIs('tenant.pesanan.online*') ? 'active' : '' }}">
            <i data-lucide="shopping-bag"></i>
            <span>Pesanan Online</span>
        </a>

        <a href="{{ route('tenant.riwayat') }}"
           class="{{ request()->routeIs('tenant.riwayat') ? 'active' : '' }}">
            <i data-lucide="history"></i>
            <span>Riwayat Transaksi</span>
        </a>

        <a href="{{ route('tenant.laporan') }}"
           class="{{ request()->routeIs('tenant.laporan') ? 'active' : '' }}">
            <i data-lucide="chart-no-axes-combined"></i>
            <span>Laporan</span>
        </a>

        <a href="{{ route('tenant.pengaturan') }}"
           class="{{ request()->routeIs('tenant.pengaturan*') ? 'active' : '' }}">
            <i data-lucide="settings"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('login.internal.logout') }}">
            @csrf
            <button type="submit" class="tenant-logout">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>