@php
    $adminProfile = auth('internal')->user();
@endphp

<div class="admin-topbar-profile">
    <button type="button" class="notification" aria-label="Notifikasi">
        <i data-lucide="bell"></i>
        <span class="notification-dot"></span>
    </button>

    <details class="admin-profile-menu">
        <summary class="admin-profile-trigger">
            <div class="admin-avatar">
                {{ strtoupper(substr($adminProfile->nama ?? 'A', 0, 1)) }}
            </div>

            <div class="admin-profile-text">
                <strong>{{ $adminProfile->nama ?? 'Admin Utama' }}</strong>
                <small>Kasir Utama / Admin</small>
            </div>

            <i data-lucide="chevron-down" class="admin-profile-chevron"></i>
        </summary>

        <div class="admin-profile-dropdown">
            <div class="admin-profile-dropdown-header">
                <div class="admin-profile-dropdown-avatar">
                    {{ strtoupper(substr($adminProfile->nama ?? 'A', 0, 1)) }}
                </div>

                <div>
                    <strong>{{ $adminProfile->nama ?? 'Admin Utama' }}</strong>
                    <span>{{ $adminProfile->email ?? '-' }}</span>
                </div>
            </div>

            <div class="admin-profile-dropdown-menu">
                <a href="{{ route('admin.pengaturan') }}">
                    <i data-lucide="settings"></i>
                    <span>Pengaturan</span>
                </a>

                <form method="POST" action="{{ route('login.internal.logout') }}">
                    @csrf
                    <button type="submit">
                        <i data-lucide="log-out"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </details>
</div>