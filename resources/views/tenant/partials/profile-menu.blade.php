@php
    $profileUser = auth('internal')->user();
@endphp

<details class="tenant-profile-menu">
    <summary class="tenant-profile-trigger">
        <div class="admin-avatar">
            {{ strtoupper(substr($tenant->nama_tenant, 0, 1)) }}
        </div>

        <strong>{{ $tenant->nama_tenant }}</strong>

        <i data-lucide="chevron-down" class="tenant-profile-chevron"></i>
    </summary>

    <div class="tenant-profile-dropdown">
        <div class="tenant-profile-dropdown-header">
            <div class="tenant-profile-dropdown-avatar">
                {{ strtoupper(substr($tenant->nama_tenant, 0, 1)) }}
            </div>

            <div>
                <strong>{{ $tenant->nama_tenant }}</strong>
                <span>{{ $profileUser->email }}</span>
            </div>
        </div>

        <div class="tenant-profile-dropdown-menu">
            <a href="{{ route('tenant.pengaturan') }}">
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