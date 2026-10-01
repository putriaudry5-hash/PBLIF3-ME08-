<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'periode' => ['nullable', 'in:harian,mingguan,bulanan'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'minggu' => ['nullable', 'regex:/^\d{4}-W\d{2}$/'],
            'bulan' => ['nullable', 'date_format:Y-m'],
            'tenant_id' => ['nullable', 'exists:tenants,id'],
            'rekap_tenant_id' => ['nullable', 'exists:tenants,id'],
        ]);

        $semuaTenants = Tenant::orderBy('nama_tenant')->get();

        $periode = $request->input('periode', 'harian');

        $tanggalInput = $request->input(
            'tanggal',
            now()->format('Y-m-d')
        );

        $mingguInput = $request->input(
            'minggu',
            now()->format('o-\WW')
        );

        $bulanInput = $request->input(
            'bulan',
            now()->format('Y-m')
        );

        $tenantFilterId = $request->filled('tenant_id')
            ? (int) $request->tenant_id
            : null;

        /*
        |--------------------------------------------------------------------------
        | PERIODE UTAMA UNTUK SUMMARY + REKAP
        |--------------------------------------------------------------------------
        */

        [
            $tanggalMulai,
            $tanggalAkhir,
            $periodeLabel
        ] = $this->tentukanPeriode(
            $periode,
            $tanggalInput,
            $mingguInput,
            $bulanInput
        );

        $transaksi = $this->ambilTransaksi(
            $tanggalMulai,
            $tanggalAkhir,
            $tenantFilterId
        );

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalTransaksi = $transaksi->count();

        $totalPendapatan = $transaksi->sum('total');

        $transaksiOnline = $transaksi->where(
            'jenis',
            'online'
        );

        $transaksiOffline = $transaksi->where(
            'jenis',
            'offline'
        );

        $jumlahOnline = $transaksiOnline->count();

        $jumlahOffline = $transaksiOffline->count();

        $pendapatanOnline = $transaksiOnline->sum(
            'total'
        );

        $pendapatanOffline = $transaksiOffline->sum(
            'total'
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE GRAFIK HARIAN
        |--------------------------------------------------------------------------
        */

        [
            $harianMulai,
            $harianAkhir,
            $harianPeriodeLabel
        ] = $this->tentukanPeriode(
            'harian',
            $tanggalInput,
            $mingguInput,
            $bulanInput
        );

        $transaksiHarian = $this->ambilTransaksi(
            $harianMulai,
            $harianAkhir,
            $tenantFilterId
        );

        [
            $grafikHarianLabel,
            $grafikHarianData
        ] = $this->buatGrafikHarian(
            $transaksiHarian,
            $semuaTenants,
            $tenantFilterId
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE GRAFIK MINGGUAN
        |--------------------------------------------------------------------------
        */

        [
            $mingguanMulai,
            $mingguanAkhir,
            $mingguanPeriodeLabel
        ] = $this->tentukanPeriode(
            'mingguan',
            $tanggalInput,
            $mingguInput,
            $bulanInput
        );

        $transaksiMingguan = $this->ambilTransaksi(
            $mingguanMulai,
            $mingguanAkhir,
            $tenantFilterId
        );

        [
            $grafikMingguanLabel,
            $grafikMingguanData
        ] = $this->buatGrafikMingguan(
            $transaksiMingguan
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE GRAFIK BULANAN
        |--------------------------------------------------------------------------
        */

        [
            $bulananMulai,
            $bulananAkhir,
            $bulananPeriodeLabel
        ] = $this->tentukanPeriode(
            'bulanan',
            $tanggalInput,
            $mingguInput,
            $bulanInput
        );

        $transaksiBulanan = $this->ambilTransaksi(
            $bulananMulai,
            $bulananAkhir,
            $tenantFilterId
        );

        [
            $grafikBulananLabel,
            $grafikBulananData
        ] = $this->buatGrafikBulanan(
            $transaksiBulanan,
            $bulananMulai
        );

        /*
        |--------------------------------------------------------------------------
        | REKAP DETAIL PER TENANT
        |--------------------------------------------------------------------------
        */

        $rekapTenantId = $request->filled(
            'rekap_tenant_id'
        )
            ? (int) $request->rekap_tenant_id
            : null;

        $tenantRekapTerpilih = null;

        $rekapTenantData = null;

        $transaksiRekapTenant = collect();

        if ($rekapTenantId) {
            $tenantRekapTerpilih = $semuaTenants
                ->firstWhere(
                    'id',
                    $rekapTenantId
                );

            $transaksiRekapTenant = Transaksi::with([
                'tenant',
                'pelanggan',
                'details'
            ])
                ->where('status', 'lunas')
                ->where(
                    'tenant_id',
                    $rekapTenantId
                )
                ->whereBetween(
                    'created_at',
                    [
                        $tanggalMulai,
                        $tanggalAkhir
                    ]
                )
                ->latest('created_at')
                ->get();

            $rekapOnline = $transaksiRekapTenant
                ->where(
                    'jenis',
                    'online'
                );

            $rekapOffline = $transaksiRekapTenant
                ->where(
                    'jenis',
                    'offline'
                );

            $totalRekap = $transaksiRekapTenant
                ->count();

            $pendapatanRekap = $transaksiRekapTenant
                ->sum(
                    'total'
                );

            $rekapTenantData = [
                'total_transaksi' =>
                    $totalRekap,

                'total_pendapatan' =>
                    $pendapatanRekap,

                'jumlah_online' =>
                    $rekapOnline->count(),

                'pendapatan_online' =>
                    $rekapOnline->sum(
                        'total'
                    ),

                'jumlah_offline' =>
                    $rekapOffline->count(),

                'pendapatan_offline' =>
                    $rekapOffline->sum(
                        'total'
                    ),

                'rata_rata' =>
                    $totalRekap > 0
                        ? round(
                            $pendapatanRekap /
                            $totalRekap
                        )
                        : 0,

                'transaksi_terakhir' =>
                    $transaksiRekapTenant
                        ->first(),
            ];
        }

        return view(
            'admin.laporan',
            compact(
                'semuaTenants',
                'periode',
                'periodeLabel',
                'tanggalInput',
                'mingguInput',
                'bulanInput',

                'totalTransaksi',
                'totalPendapatan',
                'jumlahOnline',
                'jumlahOffline',
                'pendapatanOnline',
                'pendapatanOffline',

                'harianPeriodeLabel',
                'mingguanPeriodeLabel',
                'bulananPeriodeLabel',

                'grafikHarianLabel',
                'grafikHarianData',

                'grafikMingguanLabel',
                'grafikMingguanData',

                'grafikBulananLabel',
                'grafikBulananData',

                'rekapTenantId',
                'tenantRekapTerpilih',
                'rekapTenantData',
                'transaksiRekapTenant'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL TRANSAKSI LUNAS
    |--------------------------------------------------------------------------
    */

    private function ambilTransaksi(
        Carbon $mulai,
        Carbon $akhir,
        ?int $tenantId = null
    ) {
        $query = Transaksi::with('tenant')
            ->where('status', 'lunas')
            ->whereBetween(
                'created_at',
                [
                    $mulai,
                    $akhir
                ]
            );

        if ($tenantId) {
            $query->where(
                'tenant_id',
                $tenantId
            );
        }

        return $query
            ->orderBy('created_at')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | PERIODE
    |--------------------------------------------------------------------------
    */

    private function tentukanPeriode(
        string $periode,
        string $tanggalInput,
        string $mingguInput,
        string $bulanInput
    ): array {
        if ($periode === 'mingguan') {
            [
                $tahun,
                $minggu
            ] = explode(
                '-W',
                $mingguInput
            );

            $tanggal = Carbon::now()
                ->setISODate(
                    (int) $tahun,
                    (int) $minggu
                );

            $mulai = $tanggal
                ->copy()
                ->startOfWeek();

            $akhir = $tanggal
                ->copy()
                ->endOfWeek();

            return [
                $mulai,
                $akhir,
                $mulai->format('d/m/Y') .
                ' - ' .
                $akhir->format('d/m/Y')
            ];
        }

        if ($periode === 'bulanan') {
            $tanggal = Carbon::createFromFormat(
                '!Y-m',
                $bulanInput
            );

            $mulai = $tanggal
                ->copy()
                ->startOfMonth();

            $akhir = $tanggal
                ->copy()
                ->endOfMonth();

            return [
                $mulai,
                $akhir,
                $this->namaBulan(
                    (int) $mulai->format('m')
                ) .
                ' ' .
                $mulai->format('Y')
            ];
        }

        $mulai = Carbon::parse(
            $tanggalInput
        )->startOfDay();

        $akhir = Carbon::parse(
            $tanggalInput
        )->endOfDay();

        return [
            $mulai,
            $akhir,
            $mulai->format('d/m/Y')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GRAFIK HARIAN
    |--------------------------------------------------------------------------
    */

    private function buatGrafikHarian(
        $transaksi,
        $semuaTenants,
        ?int $tenantFilterId
    ): array {
        $label = [];
        $data = [];

        /*
        | Kalau 1 tenant dipilih,
        | tampilkan Online vs Offline.
        */
        if ($tenantFilterId) {
            $label = [
                'Online',
                'Offline'
            ];

            $data = [
                $transaksi
                    ->where(
                        'jenis',
                        'online'
                    )
                    ->sum(
                        'total'
                    ),

                $transaksi
                    ->where(
                        'jenis',
                        'offline'
                    )
                    ->sum(
                        'total'
                    ),
            ];

            return [
                $label,
                $data
            ];
        }

        /*
        | Kalau Semua Tenant,
        | bandingkan pendapatan tiap tenant.
        */
        foreach ($semuaTenants as $tenant) {
            $label[] =
                $tenant->nama_tenant;

            $data[] = $transaksi
                ->where(
                    'tenant_id',
                    $tenant->id
                )
                ->sum(
                    'total'
                );
        }

        return [
            $label,
            $data
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GRAFIK MINGGUAN
    |--------------------------------------------------------------------------
    */

    private function buatGrafikMingguan(
        $transaksi
    ): array {
        $namaHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        $label = [];
        $data = [];

        for (
            $hari = 1;
            $hari <= 7;
            $hari++
        ) {
            $label[] =
                $namaHari[$hari];

            $data[] = $transaksi
                ->filter(
                    fn ($item) =>
                        $item
                            ->created_at
                            ->dayOfWeekIso ===
                        $hari
                )
                ->sum(
                    'total'
                );
        }

        return [
            $label,
            $data
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GRAFIK BULANAN
    |--------------------------------------------------------------------------
    */

    private function buatGrafikBulanan(
        $transaksi,
        Carbon $tanggalMulai
    ): array {
        $label = [];
        $data = [];

        for (
            $hari = 1;
            $hari <= $tanggalMulai->daysInMonth;
            $hari++
        ) {
            $label[] = str_pad(
                $hari,
                2,
                '0',
                STR_PAD_LEFT
            );

            $data[] = $transaksi
                ->filter(
                    fn ($item) =>
                        $item
                            ->created_at
                            ->day ===
                        $hari
                )
                ->sum(
                    'total'
                );
        }

        return [
            $label,
            $data
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | NAMA BULAN
    |--------------------------------------------------------------------------
    */

    private function namaBulan(
        int $bulan
    ): string {
        $nama = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $nama[$bulan] ?? '';
    }
}