<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin | KantinKita</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

<div class="admin-layout">

    <!-- =====================================
         SIDEBAR
    ====================================== -->
    <aside class="sidebar">

        <div class="sidebar-logo">

            <div class="logo-mark">
                K
            </div>

            <div>
                <h2>
                    Kantin<span>Kita</span>
                </h2>

                <small>
                    Kasir Utama / Admin
                </small>
            </div>

        </div>


        <nav class="sidebar-menu">

            <a href="#" class="active">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a href="#">
                <i data-lucide="store"></i>
                <span>Data Tenant</span>
            </a>

            <a href="#">
                <i data-lucide="users"></i>
                <span>Data Pengguna</span>
            </a>

            <a href="#">
                <i data-lucide="receipt-text"></i>
                <span>Transaksi Offline</span>

                <small class="menu-badge">
                    5
                </small>
            </a>

            <a href="#">
                <i data-lucide="credit-card"></i>
                <span>Transaksi Online</span>
            </a>

            <a href="#">
                <i data-lucide="history"></i>
                <span>Riwayat Transaksi</span>
            </a>

            <a href="#">
                <i data-lucide="chart-no-axes-combined"></i>
                <span>Laporan</span>
            </a>

            <a href="#">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a href="{{ route('login.internal') }}">
                <i data-lucide="log-out"></i>
                <span>Keluar</span>
            </a>

        </div>

    </aside>



    <!-- =====================================
         MAIN CONTENT
    ====================================== -->
    <div class="admin-main">


        <!-- =====================================
             TOPBAR
        ====================================== -->
        <header class="topbar">

            <div class="topbar-title">

                <h1>
                    Dashboard
                </h1>

                <p>
                    Ringkasan aktivitas KantinKita hari ini.
                </p>

            </div>


            <div class="admin-profile">

                <button
                    type="button"
                    class="notification"
                    aria-label="Notifikasi"
                >
                    <i data-lucide="bell"></i>

                    <span class="notification-dot"></span>
                </button>


                <div class="admin-avatar">
                    A
                </div>


                <div class="admin-info">

                    <strong>
                        Admin Utama
                    </strong>

                    <small>
                        Kasir Utama / Admin
                    </small>

                </div>

            </div>

        </header>



        <!-- =====================================
             STATISTIK
        ====================================== -->
        <section class="stat-grid">


            <!-- TOTAL TENANT -->
            <div class="stat-card stat-blue">

                <div class="stat-icon">
                    <i data-lucide="store"></i>
                </div>

                <div>

                    <p>
                        Total Tenant Aktif
                    </p>

                    <h2>
                        8
                    </h2>

                    <small>
                        Seluruh tenant kantin
                    </small>

                </div>

            </div>



            <!-- TRANSAKSI HARI INI -->
            <div class="stat-card stat-orange">

                <div class="stat-icon">
                    <i data-lucide="receipt-text"></i>
                </div>

                <div>

                    <p>
                        Transaksi Hari Ini
                    </p>

                    <h2>
                        28
                    </h2>

                    <small>
                        Online & Offline
                    </small>

                </div>

            </div>



            <!-- MENUNGGU PEMBAYARAN -->
            <div class="stat-card stat-yellow">

                <div class="stat-icon">
                    <i data-lucide="clock-3"></i>
                </div>

                <div>

                    <p>
                        Menunggu Pembayaran
                    </p>

                    <h2>
                        5
                    </h2>

                    <small>
                        Transaksi offline
                    </small>

                </div>

            </div>



            <!-- PENDAPATAN -->
            <div class="stat-card stat-green">

                <div class="stat-icon">
                    <i data-lucide="wallet-cards"></i>
                </div>

                <div>

                    <p>
                        Pendapatan Hari Ini
                    </p>

                    <h2>
                        Rp 450.000
                    </h2>

                    <small>
                        Seluruh tenant
                    </small>

                </div>

            </div>

        </section>



        <!-- =====================================
             TRANSAKSI + AKSES CEPAT
        ====================================== -->
        <section class="dashboard-grid">


            <!-- =====================================
                 TRANSAKSI TERBARU
            ====================================== -->
            <div class="panel transaction-panel">

                <div class="panel-header">

                    <div>

                        <h2>
                            Transaksi Terbaru
                        </h2>

                        <p>
                            Transaksi online dan offline terbaru.
                        </p>

                    </div>


                    <a href="#">
                        Lihat Semua →
                    </a>

                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>
                                <th>Kode</th>
                                <th>Waktu</th>
                                <th>Tenant</th>
                                <th>Total</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>


                        <tbody>


                            <!-- TRANSAKSI 1 -->
                            <tr>

                                <td>
                                    <strong>
                                        #OFF23015
                                    </strong>
                                </td>

                                <td>
                                    10:24
                                </td>

                                <td>
                                    Dapur Bu Sari
                                </td>

                                <td>
                                    Rp 39.000
                                </td>

                                <td>

                                    <span class="type-offline">
                                        Offline
                                    </span>

                                </td>

                                <td>

                                    <span class="status-waiting">
                                        Menunggu Bayar
                                    </span>

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="btn-table"
                                    >
                                        Proses
                                    </button>

                                </td>

                            </tr>



                            <!-- TRANSAKSI 2 -->
                            <tr>

                                <td>

                                    <strong>
                                        #TRX00124
                                    </strong>

                                </td>

                                <td>
                                    10:15
                                </td>

                                <td>
                                    Kantin Sehat
                                </td>

                                <td>
                                    Rp 28.000
                                </td>

                                <td>

                                    <span class="type-online">
                                        Online
                                    </span>

                                </td>

                                <td>

                                    <span class="status-success">
                                        Dibayar
                                    </span>

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="btn-detail"
                                    >
                                        Lihat
                                    </button>

                                </td>

                            </tr>



                            <!-- TRANSAKSI 3 -->
                            <tr>

                                <td>

                                    <strong>
                                        #OFF23014
                                    </strong>

                                </td>

                                <td>
                                    09:58
                                </td>

                                <td>
                                    Warung Barokah
                                </td>

                                <td>
                                    Rp 42.000
                                </td>

                                <td>

                                    <span class="type-offline">
                                        Offline
                                    </span>

                                </td>

                                <td>

                                    <span class="status-success">
                                        Lunas
                                    </span>

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="btn-detail"
                                    >
                                        Lihat
                                    </button>

                                </td>

                            </tr>



                            <!-- TRANSAKSI 4 -->
                            <tr>

                                <td>

                                    <strong>
                                        #TRX00123
                                    </strong>

                                </td>

                                <td>
                                    09:40
                                </td>

                                <td>
                                    Kantin Maju
                                </td>

                                <td>
                                    Rp 22.000
                                </td>

                                <td>

                                    <span class="type-online">
                                        Online
                                    </span>

                                </td>

                                <td>

                                    <span class="status-process">
                                        Diproses
                                    </span>

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="btn-detail"
                                    >
                                        Lihat
                                    </button>

                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>



            <!-- =====================================
                 AKSES CEPAT
            ====================================== -->
            <div class="panel quick-panel">

                <div class="panel-header">

                    <div>

                        <h2>
                            Akses Cepat
                        </h2>

                        <p>
                            Menu yang sering digunakan.
                        </p>

                    </div>

                </div>


                <div class="quick-list">


                    <!-- TRANSAKSI OFFLINE -->
                    <a
                        href="#"
                        class="quick-item"
                    >

                        <div class="quick-icon orange">

                            <i data-lucide="receipt-text"></i>

                        </div>


                        <div>

                            <strong>
                                Transaksi Offline
                            </strong>

                            <span>
                                Cari kode & konfirmasi cash
                            </span>

                        </div>


                        <i
                            class="quick-arrow"
                            data-lucide="chevron-right"
                        ></i>

                    </a>



                    <!-- DATA TENANT -->
                    <a
                        href="#"
                        class="quick-item"
                    >

                        <div class="quick-icon blue">

                            <i data-lucide="store"></i>

                        </div>


                        <div>

                            <strong>
                                Data Tenant
                            </strong>

                            <span>
                                Kelola akun tenant
                            </span>

                        </div>


                        <i
                            class="quick-arrow"
                            data-lucide="chevron-right"
                        ></i>

                    </a>



                    <!-- LAPORAN -->
                    <a
                        href="#"
                        class="quick-item"
                    >

                        <div class="quick-icon green">

                            <i data-lucide="chart-no-axes-combined"></i>

                        </div>


                        <div>

                            <strong>
                                Laporan
                            </strong>

                            <span>
                                Lihat rekap seluruh tenant
                            </span>

                        </div>


                        <i
                            class="quick-arrow"
                            data-lucide="chevron-right"
                        ></i>

                    </a>

                </div>

            </div>

        </section>



        <!-- =====================================
             TRANSAKSI OFFLINE MENUNGGU
        ====================================== -->
        <section class="panel offline-panel">

            <div class="panel-header">

                <div>

                    <h2>
                        Transaksi Offline Menunggu Pembayaran
                    </h2>

                    <p>
                        Pesanan yang sudah dicatat Tenant dan belum
                        dikonfirmasi pembayaran oleh Kasir.
                    </p>

                </div>


                <span class="counter-badge">
                    5 Menunggu
                </span>

            </div>



            <div class="offline-grid">


                <!-- CARD 1 -->
                <div class="offline-card">

                    <div class="offline-top">

                        <div>

                            <small>
                                Kode Transaksi
                            </small>

                            <h3>
                                #OFF23015
                            </h3>

                        </div>


                        <span class="status-waiting">
                            Menunggu Bayar
                        </span>

                    </div>


                    <div class="offline-data">

                        <div>

                            <small>
                                Tenant
                            </small>

                            <strong>
                                Dapur Bu Sari
                            </strong>

                        </div>


                        <div>

                            <small>
                                Total
                            </small>

                            <strong>
                                Rp 39.000
                            </strong>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="offline-button"
                    >
                        Proses Pembayaran
                    </button>

                </div>



                <!-- CARD 2 -->
                <div class="offline-card">

                    <div class="offline-top">

                        <div>

                            <small>
                                Kode Transaksi
                            </small>

                            <h3>
                                #OFF23012
                            </h3>

                        </div>


                        <span class="status-waiting">
                            Menunggu Bayar
                        </span>

                    </div>


                    <div class="offline-data">

                        <div>

                            <small>
                                Tenant
                            </small>

                            <strong>
                                Kantin Sehat
                            </strong>

                        </div>


                        <div>

                            <small>
                                Total
                            </small>

                            <strong>
                                Rp 28.000
                            </strong>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="offline-button"
                    >
                        Proses Pembayaran
                    </button>

                </div>



                <!-- CARD 3 -->
                <div class="offline-card">

                    <div class="offline-top">

                        <div>

                            <small>
                                Kode Transaksi
                            </small>

                            <h3>
                                #OFF23010
                            </h3>

                        </div>


                        <span class="status-waiting">
                            Menunggu Bayar
                        </span>

                    </div>


                    <div class="offline-data">

                        <div>

                            <small>
                                Tenant
                            </small>

                            <strong>
                                Warung Barokah
                            </strong>

                        </div>


                        <div>

                            <small>
                                Total
                            </small>

                            <strong>
                                Rp 35.000
                            </strong>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="offline-button"
                    >
                        Proses Pembayaran
                    </button>

                </div>


            </div>

        </section>

    </div>

</div>



<!-- =====================================
     LUCIDE ICON
====================================== -->
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>

</body>

</html>