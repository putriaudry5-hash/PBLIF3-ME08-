<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Transaksi Offline Tenant | KantinKita
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/tenant.css') }}"
    >
</head>

<body>
@php
    $user = auth('internal')->user();
@endphp

<div class="admin-layout">

@include('tenant.partials.sidebar')

    <main class="admin-main">

        <header class="topbar">

            <div class="topbar-title">

                <h1>Transaksi Offline</h1>

                <p>
                    Catat transaksi pelanggan
                    {{ $tenant->nama_tenant }}.
                </p>

            </div>

            @include('tenant.partials.profile-menu')

        </header>

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
                        Berikan kode
                        <b>
                            {{ session(
                                'kode_transaksi'
                            ) }}
                        </b>
                        kepada pelanggan untuk
                        pembayaran di Kasir Utama.
                    </p>

                </div>

                <div class="tenant-code-result">
                    {{ session(
                        'kode_transaksi'
                    ) }}
                </div>

            </div>

        @endif

        @if ($errors->any())

            <div class="tenant-error-alert">

                <i data-lucide="circle-alert"></i>

                <div>
                    <strong>
                        Transaksi belum dapat disimpan.
                    </strong>

                    <ul>
                        @foreach (
                            $errors->all()
                            as $error
                        )
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

        @endif

        <section class="tenant-stat-grid">

            <div class="tenant-small-stat">
                <span>Transaksi Hari Ini</span>
                <strong>
                    {{ $jumlahHariIni }}
                </strong>
            </div>

            <div class="tenant-small-stat">
                <span>Menunggu Pembayaran</span>
                <strong>
                    {{ $jumlahMenunggu }}
                </strong>
            </div>

            <div class="tenant-small-stat">
                <span>Transaksi Lunas</span>
                <strong>
                    {{ $jumlahLunas }}
                </strong>
            </div>

        </section>

        <section class="panel tenant-form-panel">

            <div class="panel-header">

                <div>
                    <h2>Buat Transaksi Baru</h2>

                    <p>
                        Masukkan item yang diambil
                        pelanggan.
                    </p>
                </div>

                <span class="tenant-cash-badge">
                    <i data-lucide="banknote"></i>
                    Bayar di Kasir
                </span>

            </div>

            <form
                method="POST"
                action="{{ route(
                    'tenant.transaksi.offline.store'
                ) }}"
                id="tenantTransactionForm"
            >
                @csrf

                <div class="tenant-form-body">

                    <div class="tenant-item-header">

                        <h3>Daftar Item</h3>

                        <button
                            type="button"
                            class="tenant-add-item"
                            id="addItemButton"
                        >
                            <i data-lucide="plus"></i>
                            Tambah Item
                        </button>

                    </div>

                    <div id="itemRows"></div>

                    <div class="tenant-note-group">

                        <label for="catatan">
                            Catatan
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="3"
                            placeholder="Catatan transaksi (opsional)"
                        >{{ old('catatan') }}</textarea>

                    </div>

                    <div class="tenant-total-area">

                        <div>
                            <span>Total Transaksi</span>

                            <strong id="grandTotal">
                                Rp 0
                            </strong>
                        </div>

                        <button
                            type="submit"
                            class="tenant-primary-button"
                        >
                            <i data-lucide="save"></i>
                            Buat Transaksi
                        </button>

                    </div>

                </div>

            </form>

        </section>

        <section class="panel tenant-panel">

            <div class="panel-header">

                <div>
                    <h2>Transaksi Hari Ini</h2>

                    <p>
                        Daftar transaksi yang telah
                        dibuat hari ini.
                    </p>
                </div>

            </div>

            <div class="tenant-table-wrapper">

                <table class="tenant-table">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse (
                            $transaksiHariIni
                            as $transaksi
                        )

                            <tr>

                                <td>
                                    <strong>
                                        {{ $transaksi
                                            ->kode_transaksi }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $transaksi
                                        ->created_at
                                        ?->format('H:i') }}
                                </td>

                                <td>
                                    {{ $transaksi
                                        ->details
                                        ->sum('jumlah') }}
                                    item
                                </td>

                                <td>
                                    Rp {{ number_format(
                                        $transaksi->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </td>

                                <td>

                                    @if (
                                        $transaksi->status ===
                                        'lunas'
                                    )

                                        <span
                                            class="status-success"
                                        >
                                            Lunas
                                        </span>

                                    @else

                                        <span
                                            class="status-waiting"
                                        >
                                            Menunggu Bayar
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="tenant-empty-table"
                                >
                                    Belum ada transaksi
                                    hari ini.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>

<script id="tenantMenusData" type="application/json">
{!! json_encode($menus, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<script src="{{ asset('js/tenant-transaksi.js') }}"></script>

</body>
</html>