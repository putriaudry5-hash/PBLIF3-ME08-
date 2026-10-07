<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu Tenant | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
</head>

<body>

@php
    $user = auth('internal')->user();
@endphp

<div class="admin-layout">

    {{-- SIDEBAR --}}
    @include('tenant.partials.sidebar')

    {{-- MAIN --}}
    <main class="admin-main">

        {{-- TOPBAR --}}
        <header class="topbar">
    <div class="topbar-title">
        <h1>Kelola Menu</h1>
        <p>Atur menu yang tersedia di {{ $tenant->nama_tenant }}.</p>
    </div>

    @include('tenant.partials.profile-menu')
</header>


        {{-- ALERT SUCCESS --}}
        @if (session('success'))

            <div class="tenant-success-alert">

                <div class="tenant-success-icon">
                    <i data-lucide="circle-check"></i>
                </div>

                <div>
                    <strong>
                        {{ session('success') }}
                    </strong>

                    <p>
                        Perubahan menu berhasil disimpan.
                    </p>
                </div>

            </div>

        @endif


        {{-- ALERT ERROR --}}
        @if ($errors->any())

            <div class="tenant-error-alert">

                <i data-lucide="circle-alert"></i>

                <div>

                    <strong>
                        Data menu belum dapat disimpan.
                    </strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>

                </div>

            </div>

        @endif


        {{-- STATISTIK --}}
        <section class="tenant-menu-stat-grid">

            <div class="tenant-small-stat">

                <span>
                    Total Menu
                </span>

                <strong>
                    {{ $menus->count() }}
                </strong>

            </div>


            <div class="tenant-small-stat">

                <span>
                    Menu Satuan
                </span>

                <strong>
                    {{ $menuSatuan->count() }}
                </strong>

            </div>


            <div class="tenant-small-stat">

                <span>
                    Menu Prasmanan
                </span>

                <strong>
                    {{ $menuPrasmanan->count() }}
                </strong>

            </div>


            <div class="tenant-small-stat">

                <span>
                    Menu Tersedia
                </span>

                <strong>
                    {{ $menus->where('status', 'tersedia')->count() }}
                </strong>

            </div>

        </section>


        {{-- ACTION --}}
        <section class="tenant-action-panel">

            <div>

                <div class="tenant-action-icon">
                    <i data-lucide="utensils"></i>
                </div>

                <div>

                    <h2>
                        Daftar Menu Tenant
                    </h2>

                    <p>
                        Tambah dan kelola menu satuan maupun prasmanan.
                    </p>

                </div>

            </div>


            <button
                type="button"
                class="tenant-primary-button"
                data-open-modal="createMenuModal"
            >
                <i data-lucide="plus"></i>
                Tambah Menu
            </button>

        </section>


        {{-- DAFTAR MENU --}}
        <section class="panel tenant-panel tenant-menu-panel">

 {{-- DAFTAR MENU --}}
<section class="panel tenant-panel tenant-menu-panel">

    <div class="panel-header">
        <div>
            <h2>Menu</h2>
            <p>Menu yang tersedia pada {{ $tenant->nama_tenant }}.</p>
        </div>

        <div class="tenant-menu-tabs">
            <button
                type="button"
                class="tenant-menu-tab menu-tab active"
                data-menu-filter="semua"
            >
                Semua
            </button>

            <button
                type="button"
                class="tenant-menu-tab menu-tab"
                data-menu-filter="satuan"
            >
                Satuan
            </button>

            <button
                type="button"
                class="tenant-menu-tab menu-tab"
                data-menu-filter="prasmanan"
            >
                Prasmanan
            </button>
        </div>
    </div>

    <div class="tenant-menu-body">
        <div class="tenant-menu-grid">

            @forelse ($menus as $menu)

                <article
                    class="tenant-menu-card menu-card"
                    data-menu-type="{{ $menu->jenis_menu }}"
                >

                            {{-- FOTO --}}
                            <div class="tenant-menu-photo">

                                @if ($menu->foto)

                                    <img
                                        src="{{ asset(
                                            'storage/' . $menu->foto
                                        ) }}"
                                        alt="{{ $menu->nama_menu }}"
                                    >

                                @else

                                    <div class="tenant-menu-no-photo">

                                        <i data-lucide="image"></i>

                                        <span>
                                            Belum ada foto
                                        </span>

                                    </div>

                                @endif


                                <div class="tenant-menu-badges">

                                    <span
                                        class="tenant-menu-type
                                        {{ $menu->jenis_menu === 'satuan'
                                            ? 'satuan'
                                            : 'prasmanan' }}"
                                    >
                                        {{ ucfirst(
                                            $menu->jenis_menu
                                        ) }}
                                    </span>


                                    <span
                                        class="tenant-menu-stock-status
                                        {{ $menu->status === 'tersedia'
                                            ? 'tersedia'
                                            : 'habis' }}"
                                    >
                                        {{ $menu->status === 'tersedia'
                                            ? 'Tersedia'
                                            : 'Habis' }}
                                    </span>

                                </div>

                            </div>


                            {{-- BODY --}}
                            <div class="tenant-menu-card-body">

                                <div class="tenant-menu-title-row">

                                    <div>

                                        <h3>
                                            {{ $menu->nama_menu }}
                                        </h3>

                                        <span>
                                            @if ($menu->tipe_harga === 'fleksibel')
                                                Harga fleksibel
                                            @elseif ($menu->tipe_harga === 'per_potong')
                                                Harga per potong
                                            @else
                                                Harga tetap
                                            @endif
                                        </span>

                                    </div>


                                    <div
                                        class="tenant-menu-online
                                        {{ $menu->aktif_online
                                            ? 'aktif'
                                            : 'nonaktif' }}"
                                    >
                                        {{ $menu->aktif_online
                                            ? 'Online'
                                            : 'Nonaktif' }}
                                    </div>

                                </div>


                                {{-- HARGA --}}
                                <div class="tenant-menu-price">

                                    <small>
                                        Harga
                                    </small>


                                    @if ($menu->tipe_harga === 'fleksibel')

                                        <div class="tenant-flexible-price">

                                            @foreach (
                                                ($menu->opsi_harga ?? [])
                                                as $harga
                                            )

                                                <span>
                                                    Rp {{ number_format(
                                                        $harga,
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) }}
                                                </span>

                                            @endforeach

                                        </div>

                                    @else

                                        <strong>
                                            Rp {{ number_format(
                                                $menu->harga ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </strong>

                                    @endif

                                </div>


                                @if ($menu->jenis_menu === 'satuan')

                                    <div class="tenant-menu-stock">

                                        <span>
                                            Stok
                                        </span>

                                        <strong>
                                            {{ $menu->stok ?? 0 }}
                                        </strong>

                                    </div>

                                @endif


                                {{-- ACTION BUTTONS --}}
                                <div class="tenant-menu-actions">

                                    <button
                                        type="button"
                                        class="tenant-menu-edit"
                                        data-open-modal="editMenu{{ $menu->id }}"
                                    >
                                        <i data-lucide="pencil"></i>
                                        Edit
                                    </button>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'tenant.menu.status',
                                            $menu->id
                                        ) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="tenant-menu-status-button"
                                        >
                                            @if ($menu->status === 'tersedia')

                                                <i data-lucide="circle-x"></i>
                                                Habis

                                            @else

                                                <i data-lucide="circle-check"></i>
                                                Tersedia

                                            @endif
                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'tenant.menu.online',
                                            $menu->id
                                        ) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="tenant-menu-online-button"
                                        >
                                            @if ($menu->aktif_online)

                                                <i data-lucide="wifi-off"></i>
                                                Nonaktifkan

                                            @else

                                                <i data-lucide="wifi"></i>
                                                Aktifkan

                                            @endif
                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'tenant.menu.destroy',
                                            $menu->id
                                        ) }}"
                                        class="delete-menu-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="tenant-menu-delete"
                                        >
                                            <i data-lucide="trash-2"></i>
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </article>


                        {{-- MODAL EDIT --}}
                        <div
                            class="modal tenant-menu-modal"
                            id="editMenu{{ $menu->id }}"
                        >

                            <div
                                class="tenant-menu-modal-overlay"
                                data-close-modal
                            ></div>


                            <div class="tenant-menu-modal-card">

                                <div class="tenant-menu-modal-header">

                                    <div>

                                        <span>
                                            Kelola Menu
                                        </span>

                                        <h2>
                                            Edit Menu
                                        </h2>

                                    </div>


                                    <button
                                        type="button"
                                        class="tenant-menu-modal-close"
                                        data-close-modal
                                    >
                                        <i data-lucide="x"></i>
                                    </button>

                                </div>


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'tenant.menu.update',
                                        $menu->id
                                    ) }}"
                                    enctype="multipart/form-data"
                                    class="menu-form"
                                >

                                    @csrf
                                    @method('PUT')


                                    <div class="tenant-menu-form-group">

                                        <label>
                                            Nama Menu
                                        </label>

                                        <input
                                            type="text"
                                            name="nama_menu"
                                            value="{{ $menu->nama_menu }}"
                                            required
                                        >

                                    </div>


                                    <div class="tenant-menu-form-grid">

                                        <div class="tenant-menu-form-group">

                                            <label>
                                                Jenis Menu
                                            </label>

                                            <select
                                                name="jenis_menu"
                                                class="jenis-menu"
                                                required
                                            >

                                                <option
                                                    value="satuan"
                                                    @selected(
                                                        $menu->jenis_menu === 'satuan'
                                                    )
                                                >
                                                    Menu Satuan
                                                </option>

                                                <option
                                                    value="prasmanan"
                                                    @selected(
                                                        $menu->jenis_menu === 'prasmanan'
                                                    )
                                                >
                                                    Prasmanan
                                                </option>

                                            </select>

                                        </div>


                                        <div class="tenant-menu-form-group">

                                            <label>
                                                Tipe Harga
                                            </label>

                                            <select
                                                name="tipe_harga"
                                                class="tipe-harga"
                                                required
                                            >

                                                <option
                                                    value="tetap"
                                                    @selected(
                                                        $menu->tipe_harga === 'tetap'
                                                    )
                                                >
                                                    Harga Tetap
                                                </option>

                                                <option
                                                    value="fleksibel"
                                                    @selected(
                                                        $menu->tipe_harga === 'fleksibel'
                                                    )
                                                >
                                                    Harga Fleksibel
                                                </option>

                                                <option
                                                    value="per_potong"
                                                    @selected(
                                                        $menu->tipe_harga === 'per_potong'
                                                    )
                                                >
                                                    Harga Per Potong
                                                </option>

                                            </select>

                                        </div>

                                    </div>


                                    <div
                                        class="tenant-menu-form-group normal-price-field"
                                    >

                                        <label>
                                            Harga
                                        </label>

                                        <div class="money-input">

                                            <span>
                                                Rp
                                            </span>

                                            <input
                                                type="number"
                                                name="harga"
                                                value="{{ $menu->harga }}"
                                                min="0"
                                            >

                                        </div>

                                    </div>


                                    <div
                                        class="tenant-menu-form-group flexible-price-field"
                                    >

                                        <label>
                                            Pilihan Harga Prasmanan
                                        </label>


                                        <div class="price-options-list">

                                            @forelse (
                                                ($menu->opsi_harga ?? [])
                                                as $harga
                                            )

                                                <div class="price-option-row">

                                                    <div class="money-input">

                                                        <span>
                                                            Rp
                                                        </span>

                                                        <input
                                                            type="number"
                                                            name="opsi_harga[]"
                                                            value="{{ $harga }}"
                                                            min="1"
                                                        >

                                                    </div>


                                                    <button
                                                        type="button"
                                                        class="remove-price-option"
                                                    >
                                                        ×
                                                    </button>

                                                </div>

                                            @empty

                                                <div class="price-option-row">

                                                    <div class="money-input">

                                                        <span>
                                                            Rp
                                                        </span>

                                                        <input
                                                            type="number"
                                                            name="opsi_harga[]"
                                                            min="1"
                                                        >

                                                    </div>

                                                    <button
                                                        type="button"
                                                        class="remove-price-option"
                                                    >
                                                        ×
                                                    </button>

                                                </div>

                                            @endforelse

                                        </div>


                                        <button
                                            type="button"
                                            class="add-price-option"
                                        >
                                            + Tambah pilihan harga
                                        </button>

                                    </div>


                                    <div
                                        class="tenant-menu-form-group stock-field"
                                    >

                                        <label>
                                            Stok
                                        </label>

                                        <input
                                            type="number"
                                            name="stok"
                                            value="{{ $menu->stok }}"
                                            min="0"
                                        >

                                    </div>


                                    <div class="tenant-menu-form-group">

                                        <label>
                                            Foto Menu
                                        </label>

                                        <input
                                            type="file"
                                            name="foto"
                                            accept=".jpg,.jpeg,.png,.webp"
                                        >

                                        <small>
                                            Kosongkan jika foto tidak diganti.
                                        </small>

                                    </div>


                                    <div class="tenant-menu-modal-actions">

                                        <button
                                            type="button"
                                            class="tenant-menu-cancel"
                                            data-close-modal
                                        >
                                            Batal
                                        </button>

                                        <button
                                            type="submit"
                                            class="tenant-primary-button"
                                        >
                                            <i data-lucide="save"></i>
                                            Simpan
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="tenant-menu-empty">

                            <i data-lucide="utensils"></i>

                            <h3>
                                Belum ada menu
                            </h3>

                            <p>
                                Tambahkan menu pertama untuk
                                {{ $tenant->nama_tenant }}.
                            </p>

                            <button
                                type="button"
                                class="tenant-primary-button"
                                data-open-modal="createMenuModal"
                            >
                                <i data-lucide="plus"></i>
                                Tambah Menu
                            </button>

                        </div>

                    @endforelse

                </div>

            </div>

        </section>

    </main>

</div>



{{-- MODAL TAMBAH MENU --}}
<div
    class="modal tenant-menu-modal"
    id="createMenuModal"
>

    <div
        class="tenant-menu-modal-overlay"
        data-close-modal
    ></div>


    <div class="tenant-menu-modal-card">

        <div class="tenant-menu-modal-header">

            <div>

                <span>
                    Menu Baru
                </span>

                <h2>
                    Tambah Menu
                </h2>

            </div>


            <button
                type="button"
                class="tenant-menu-modal-close"
                data-close-modal
            >
                <i data-lucide="x"></i>
            </button>

        </div>


        <form
            method="POST"
            action="{{ route('tenant.menu.store') }}"
            enctype="multipart/form-data"
            class="menu-form"
        >

            @csrf


            <div class="tenant-menu-form-group">

                <label>
                    Nama Menu
                </label>

                <input
                    type="text"
                    name="nama_menu"
                    value="{{ old('nama_menu') }}"
                    placeholder="Contoh: Ayam Goreng"
                    required
                >

            </div>


            <div class="tenant-menu-form-grid">

                <div class="tenant-menu-form-group">

                    <label>
                        Jenis Menu
                    </label>

                    <select
                        name="jenis_menu"
                        class="jenis-menu"
                        required
                    >

                        <option value="satuan">
                            Menu Satuan
                        </option>

                        <option value="prasmanan">
                            Prasmanan
                        </option>

                    </select>

                </div>


                <div class="tenant-menu-form-group">

                    <label>
                        Tipe Harga
                    </label>

                    <select
                        name="tipe_harga"
                        class="tipe-harga"
                        required
                    >

                        <option value="tetap">
                            Harga Tetap
                        </option>

                        <option value="fleksibel">
                            Harga Fleksibel
                        </option>

                        <option value="per_potong">
                            Harga Per Potong
                        </option>

                    </select>

                </div>

            </div>


            <div
                class="tenant-menu-form-group normal-price-field"
            >

                <label>
                    Harga
                </label>

                <div class="money-input">

                    <span>
                        Rp
                    </span>

                    <input
                        type="number"
                        name="harga"
                        min="0"
                        placeholder="15000"
                    >

                </div>

            </div>


            <div
                class="tenant-menu-form-group flexible-price-field"
            >

                <label>
                    Pilihan Harga Prasmanan
                </label>

                <div class="price-options-list">

                    <div class="price-option-row">

                        <div class="money-input">

                            <span>
                                Rp
                            </span>

                            <input
                                type="number"
                                name="opsi_harga[]"
                                min="1"
                                placeholder="3000"
                            >

                        </div>


                        <button
                            type="button"
                            class="remove-price-option"
                        >
                            ×
                        </button>

                    </div>

                </div>


                <button
                    type="button"
                    class="add-price-option"
                >
                    + Tambah pilihan harga
                </button>

            </div>


            <div
                class="tenant-menu-form-group stock-field"
            >

                <label>
                    Stok
                </label>

                <input
                    type="number"
                    name="stok"
                    min="0"
                    placeholder="Contoh: 20"
                >

            </div>


            <div class="tenant-menu-form-group">

                <label>
                    Foto Menu
                </label>

                <input
                    type="file"
                    name="foto"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small>
                    Maksimal 2 MB. JPG, JPEG, PNG atau WEBP.
                </small>

            </div>


            <div class="tenant-menu-modal-actions">

                <button
                    type="button"
                    class="tenant-menu-cancel"
                    data-close-modal
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="tenant-primary-button"
                >
                    <i data-lucide="plus"></i>
                    Tambah Menu
                </button>

            </div>

        </form>

    </div>

</div>


<script src="https://unpkg.com/lucide@latest"></script>

<script
    src="{{ asset('js/tenant-menu.js') }}"
></script>

<script>
    lucide.createIcons();
</script>

</body>
</html>