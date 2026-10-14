<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $tenant['nama'] }} - KantinKita</title>

    <link rel="stylesheet"
        href="{{ asset('css/menu-tenant.css') }}?v={{ filemtime(public_path('css/menu-tenant.css')) }}">
</head>
<body>
<main class="menu-container">
    <section class="tenant-header">
        <a href="{{ route('pelanggan.dashboard') }}" class="back-link">
            &larr; Kembali ke daftar tenant
        </a>

        <h1>{{ $tenant['nama'] }}</h1>
        <p>{{ $tenant['deskripsi'] }}</p>

        <span class="jenis-label">
            {{ $tenant['jenis'] === 'prasmanan' ? 'Menu Prasmanan' : 'Menu Satuan' }}
        </span>
    </section>

    <div id="notice" class="notice" role="status" style="display:none;"></div>

    <section class="menu-section">
        <h2>
            {{ $tenant['jenis'] === 'prasmanan' ? 'Pilih Menu dan Harga' : 'Daftar Menu' }}
        </h2>

        <div class="menu-grid">
            @forelse ($menus as $index => $menu)
                <article class="menu-card">
                    <img
                        class="menu-gambar"
                        src="{{ $menu['gambar'] }}"
                        alt="{{ $menu['nama'] }}"
                        loading="lazy"
                    >

                    <div class="menu-card-content">
                        <span class="menu-kategori">{{ $menu['kategori'] }}</span>
                        <h3>{{ $menu['nama'] }}</h3>
                        <p class="menu-deskripsi">{{ $menu['deskripsi'] }}</p>

                        @if (!empty($menu['harga_pilihan']))
                            <p class="menu-deskripsi">
                                Pilih harga sesuai porsi yang diinginkan.
                            </p>

                            <div class="pilihan-harga">
                                @foreach ($menu['harga_pilihan'] as $harga)
                                    <div class="harga-prasmanan">
                                        <span>
                                            Rp{{ number_format($harga, 0, ',', '.') }}
                                        </span>

                                        <button
                                            type="button"
                                            class="add-button"
                                            onclick="tambahKeranjang({{ $index }}, {{ $harga }})"
                                        >+</button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="menu-harga">
                                Rp{{ number_format($menu['harga'], 0, ',', '.') }}
                                <small>/ {{ $menu['satuan'] }}</small>
                            </p>

                            <button
                                type="button"
                                class="tombol-satuan"
                                onclick="tambahKeranjang({{ $index }})"
                            >+ Tambah ke Keranjang</button>
                        @endif
                    </div>
                </article>
            @empty
                <p>Belum ada menu yang tersedia.</p>
            @endforelse
        </div>
    </section>
</main>

<div class="cart-bar">
    <div>
        <strong>Keranjang Belanja</strong>
        <div id="cart-count">0 item</div>
        <div id="cart-total">Total: Rp0</div>
    </div>

    <button
        type="button"
        id="btn-lihat-keranjang"
        onclick="lihatKeranjang()"
    >Lihat Keranjang</button>
</div>

<script>
    window.tenantId = @json((int) $tenant['id']);
    window.urlTambahKeranjang = @json(route('pelanggan.keranjang.tambah'));
    window.urlKeranjang = @json(route('pelanggan.keranjang'));
</script>

<script src="{{ asset('js/menu-tenant.js') }}?v={{ filemtime(public_path('js/menu-tenant.js')) }}"></script>
</body>
</html>
