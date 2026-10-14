<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - KantinKita</title>

    <style>
        body {
            margin: 0;
            padding: 35px 8%;
            font-family: Arial, sans-serif;
            background: #fff9eb;
            color: #401522;
        }

        a {
            color: #801b32;
            text-decoration: none;
            font-weight: bold;
        }

        h1 {
            margin-bottom: 10px;
        }

        .deskripsi {
            color: #806b6d;
            margin-bottom: 30px;
        }

        .kartu {
            background: white;
            padding: 25px;
            border-radius: 18px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee5df;
            text-align: left;
        }

        th {
            background: #fff3e4;
        }

        .kosong {
            text-align: center;
            padding: 45px 10px;
            color: #806b6d;
        }

        .tombol {
            display: inline-block;
            background: #801b32;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <a href="{{ route('pelanggan.dashboard') }}">
        ← Kembali ke Beranda
    </a>

    <h1>Riwayat Transaksi</h1>

    <p class="deskripsi">
        Lihat daftar transaksi dan pembayaran yang pernah dilakukan.
    </p>

    <div class="kartu">
        @if ($riwayat->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th>Detail</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($riwayat as $pesanan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pesanan->kode_pesanan }}</td>
                            <td>
                                {{ $pesanan->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td>
                                Rp{{ number_format($pesanan->total, 0, ',', '.') }}
                            </td>
                            <td>{{ $pesanan->status_pembayaran }}</td>
                            <td>{{ $pesanan->status_pesanan }}</td>
                            <td>
                                <a href="{{ route('pelanggan.detail-pesanan', ['nomor' => $pesanan->kode_pesanan]) }}">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $riwayat->links() }}
            </div>
        @else
            <div class="kosong">
                <h2>Belum Ada Riwayat Transaksi</h2>
                <p>Transaksi yang berhasil tersimpan akan muncul di halaman ini.</p>

                <a class="tombol"
                   href="{{ route('pelanggan.dashboard') }}">
                    Mulai Pesan Menu
                </a>
            </div>
        @endif
    </div>

</body>
</html>
