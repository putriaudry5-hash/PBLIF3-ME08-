
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Transaksi - KantinKita</title>

    <link rel="stylesheet"
          href="{{ asset('css/riwayat-transaksi.css') }}?v={{ filemtime(public_path('css/riwayat-transaksi.css')) }}">
</head>
<body>
    <div class="container">

        <div class="header">
            <div>
                <h1>Riwayat Transaksi</h1>
                <p>Daftar transaksi yang tercatat di KantinKita.</p>
            </div>

            <a href="{{ route('pelanggan.dashboard') }}" class="back">
                Kembali ke Beranda
            </a>
        </div>

        <div class="card">
            @if ($riwayat->isEmpty())
                <div class="empty">
                    <h3>Belum Ada Transaksi</h3>
                    <p>Transaksi yang tersimpan akan muncul di halaman ini.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Detail</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($riwayat as $index => $pesanan)
                            <tr>
                                <td>{{ $riwayat->firstItem() + $index }}</td>

                                <td>{{ $pesanan->kode_pesanan }}</td>

                                <td>
                                    {{ $pesanan->created_at
                                        ? $pesanan->created_at->format('d-m-Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    Rp{{ number_format($pesanan->total, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ ucfirst($pesanan->status_pembayaran ?? '-') }}
                                </td>

                                <td>
                                    {{ ucfirst($pesanan->status_pesanan ?? '-') }}
                                </td>

                                <td>
                                    <a class="detail"
                                       href="{{ route('pelanggan.detail-pesanan', ['nomor' => $pesanan->kode_pesanan]) }}">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>

    </div>
</body>
</html>
