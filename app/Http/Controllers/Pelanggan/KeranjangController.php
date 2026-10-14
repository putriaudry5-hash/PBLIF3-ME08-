<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Support\KantinMenu;

class KeranjangController extends Controller
{
    private function daftarTenant(): array
    {
        return [
            1 => 'Dapur Bu Sari',
            2 => 'Kantin Sehat',
            3 => 'Warung Barokah',
            4 => 'Kantin Maju',
            5 => 'Resto Kampus',
            6 => 'Pojok Nusantara',
            7 => 'Kedai Kita',
            8 => 'Dapoer Rasa',
        ];
    }

private function jenisTenant(int $tenantId): string
{
    return KantinMenu::tenants()[$tenantId]['jenis'] ?? 'satuan';
}

    private function daftarMenu(): array
    {
        return [
            0 => [
                'nama' => 'Nasi Ayam',
                'harga' => 15000,
                'gambar' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600',
            ],
            1 => [
                'nama' => 'Nasi Goreng',
                'harga' => 12000,
                'gambar' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600',
            ],
            2 => [
                'nama' => 'Mie Goreng',
                'harga' => 10000,
                'gambar' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600',
            ],
            3 => [
                'nama' => 'Ayam Geprek',
                'harga' => 13000,
                'gambar' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=600',
            ],
            4 => [
                'nama' => 'Es Teh',
                'harga' => 4000,
                'gambar' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600',
            ],
            5 => [
                'nama' => 'Jus Jeruk',
                'harga' => 7000,
                'gambar' => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=600',
            ],
        ];
    }

    private function token(Request $request): string
    {
        return $request->cookie('kantinkita_cart')
            ?: (string) Str::uuid();
    }

    private function items(string $token)
    {
        return DB::table('keranjang_items')
            ->where('cart_token', $token)
            ->orderBy('id')
            ->get();
    }

    private function total($items): int
    {
        return (int) $items->sum(
            fn ($item) => (int) $item->harga * (int) $item->jumlah
        );
    }

    public function index(Request $request)
    {
        $token = $request->cookie('kantinkita_cart');

        $items = $token
            ? $this->items($token)
            : collect();

        return response()
            ->view('pelanggan.keranjang', [
                'items' => $items,
                'total' => $this->total($items),
            ])
            ->cookie(
                'kantinkita_cart',
                $token ?: (string) Str::uuid(),
                60 * 24 * 30
            );
    }


public function tambah(Request $request)
{
    $data = $request->validate([
        'tenant_id' => ['required', 'integer', 'between:1,8'],
        'menu_index' => ['required', 'integer', 'min:0'],
        'harga_pilihan' => [
            'nullable',
            'integer',
            'in:2000,3000,4000,5000',
        ],
    ]);

    $tenantId = (int) $data['tenant_id'];
    $menuIndex = (int) $data['menu_index'];

    $daftarTenant = KantinMenu::tenants();
    $semuaMenu = KantinMenu::menus();

    $tenant = $daftarTenant[$tenantId] ?? null;
    $menu = $semuaMenu[$tenantId][$menuIndex] ?? null;

    if (!$tenant || !$menu) {
        return response()->json([
            'message' => 'Menu tidak ditemukan.',
        ], 404);
    }

    $jenis = $tenant['jenis'];
    $pilihanHarga = $menu['harga_pilihan'] ?? [];

    if (!empty($pilihanHarga)) {
        if (
            !isset($data['harga_pilihan'])
            || !in_array(
                (int) $data['harga_pilihan'],
                $pilihanHarga,
                true
            )
        ) {
            return response()->json([
                'message' => 'Silakan pilih harga yang tersedia.',
            ], 422);
        }

        $harga = (int) $data['harga_pilihan'];
    } else {
        if (isset($data['harga_pilihan'])) {
            return response()->json([
                'message' => 'Menu ini menggunakan harga tetap.',
            ], 422);
        }

        $harga = (int) $menu['harga'];
    }

    $token = $request->cookie('kantinkita_cart')
        ?: (string) Str::uuid();

    $keranjangLama = $this->items($token);

    // Satu keranjang hanya untuk satu tenant.
    if (
        $keranjangLama->isNotEmpty()
        && (int) $keranjangLama->first()->tenant_id !== $tenantId
    ) {
        $token = (string) Str::uuid();
        $keranjangLama = collect();
    }

    $item = DB::table('keranjang_items')
        ->where('cart_token', $token)
        ->where('tenant_id', $tenantId)
        ->where('menu_index', $menuIndex)
        ->where('harga', $harga)
        ->first();

    if ($item) {
        DB::table('keranjang_items')
            ->where('id', $item->id)
            ->where('cart_token', $token)
            ->increment('jumlah');
    } else {
        DB::table('keranjang_items')->insert([
            'cart_token' => $token,
            'tenant_id' => $tenantId,
            'menu_index' => $menuIndex,
            'tenant_nama' => $tenant['nama'],
            'menu_nama' => $menu['nama'],
            'gambar' => $menu['gambar'],
            'harga' => $harga,
            'jumlah' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $items = $this->items($token);

    return response()->json([
        'message' => 'Menu berhasil ditambahkan.',
        'jumlah' => (int) $items->sum('jumlah'),
        'total' => $this->total($items),
    ])->cookie(
        'kantinkita_cart',
        $token,
        60 * 24 * 30
    );
}

    public function jumlah(Request $request, int $item)
    {
        $data = $request->validate([
            'aksi' => ['required', 'in:tambah,kurang'],
        ]);

        $token = $request->cookie('kantinkita_cart');

        $baris = DB::table('keranjang_items')
            ->where('id', $item)
            ->where('cart_token', $token)
            ->first();

        if (!$baris) {
            return back()->with('error', 'Item keranjang tidak ditemukan.');
        }

        if ($data['aksi'] === 'tambah') {
            DB::table('keranjang_items')
                ->where('id', $item)
                ->where('cart_token', $token)
                ->increment('jumlah');
        } elseif ((int) $baris->jumlah > 1) {
            DB::table('keranjang_items')
                ->where('id', $item)
                ->where('cart_token', $token)
                ->decrement('jumlah');
        } else {
            DB::table('keranjang_items')
                ->where('id', $item)
                ->where('cart_token', $token)
                ->delete();
        }

        return back();
    }

    public function hapus(Request $request, int $item)
    {
        DB::table('keranjang_items')
            ->where('id', $item)
            ->where('cart_token', $request->cookie('kantinkita_cart'))
            ->delete();

        return back();
    }

    public function kosongkan(Request $request)
    {
        DB::table('keranjang_items')
            ->where('cart_token', $request->cookie('kantinkita_cart'))
            ->delete();

        return back();
    }

    public function checkout(Request $request)
    {
        $token = $request->cookie('kantinkita_cart');

        $items = $token
            ? $this->items($token)
            : collect();

        if ($items->isEmpty()) {
            return redirect()
                ->route('pelanggan.keranjang')
                ->with('error', 'Keranjang masih kosong.');
        }

        return view('pelanggan.checkout', [
            'items' => $items,
            'total' => $this->total($items),
        ]);
    }

    public function pembayaran(Request $request)
    {
        $data = $request->validate([
            'metode' => ['required', 'in:qris,transfer,ewallet'],
        ]);

        $token = $request->cookie('kantinkita_cart');

        $items = $token
            ? $this->items($token)
            : collect();

        if ($items->isEmpty()) {
            return redirect()->route('pelanggan.keranjang');
        }

        return view('pelanggan.pembayaran', [
            'items' => $items,
            'total' => $this->total($items),
            'metode' => $data['metode'],
        ]);
    }

    public function konfirmasiPembayaran(Request $request)
    {
        $data = $request->validate([
            'metode' => ['required', 'in:qris,transfer,ewallet'],
        ]);

        $token = $request->cookie('kantinkita_cart');

        $items = $token
            ? $this->items($token)
            : collect();

        if ($items->isEmpty()) {
            return redirect()
                ->route('pelanggan.keranjang')
                ->with('error', 'Keranjang masih kosong.');
        }

        $nomor = 'KK-' . now()->format('YmdHis') . '-'
            . Str::upper(Str::random(4));

        $total = $this->total($items);

        $pesanan = DB::transaction(function () use (
            $items,
            $data,
            $nomor,
            $total
        ) {
            $pesanan = Pesanan::create([
                'kode_pesanan' => $nomor,
                'tenant_id' => (int) $items->first()->tenant_id,
                'pelanggan_id' => null,
                'total' => $total,
                'status_pembayaran' => 'berhasil',
                'status_pesanan' => 'Diproses',
                'catatan' => 'Metode pembayaran: '
                    . strtoupper($data['metode']),
                'dibayar_at' => now(),
            ]);

            foreach ($items as $item) {
                $pesanan->details()->create([
                    'menu_id' => null,
                    'nama_item' => $item->menu_nama,
                    'jenis_menu' => $this->jenisTenant(
                        (int) $item->tenant_id
                    ),
                    'harga' => (int) $item->harga,
                    'jumlah' => (int) $item->jumlah,
                    'subtotal' => (int) $item->harga
                        * (int) $item->jumlah,
                ]);
            }

            return $pesanan;
        });

        DB::table('keranjang_items')
            ->where('cart_token', $token)
            ->delete();

        return redirect()
            ->route('pelanggan.pembayaran.berhasil', [
                'nomor' => $pesanan->kode_pesanan,
            ])
            ->cookie(
                'kantinkita_cart',
                (string) Str::uuid(),
                60 * 24 * 30
            );
    }

    public function pembayaranBerhasil(Request $request)
    {
        $nomor = $request->query('nomor');

        if (!$nomor) {
            return redirect()->route('pelanggan.dashboard');
        }

        $dataPesanan = Pesanan::with(['tenant', 'details'])
            ->where('kode_pesanan', $nomor)
            ->firstOrFail();

        $pesanan = $this->formatPesanan($dataPesanan);

        return view('pelanggan.pembayaran-berhasil', [
            'pesanan' => $pesanan,
        ]);
    }

    public function detailPesanan(string $nomor)
    {
        $dataPesanan = Pesanan::with(['tenant', 'details'])
            ->where('kode_pesanan', $nomor)
            ->firstOrFail();

        $pesanan = $this->formatPesanan($dataPesanan);

        return view('pelanggan.detail-pesanan', [
            'pesanan' => $pesanan,
        ]);
    }

    private function formatPesanan(Pesanan $dataPesanan): array
    {
        return [
            'nomor' => $dataPesanan->kode_pesanan,
            'tenant_nama' => $dataPesanan->tenant->nama
                ?? $this->daftarTenant()[$dataPesanan->tenant_id]
                ?? 'Tenant',
            'items' => $dataPesanan->details->map(function ($detail) {
                return [
                    'menu_nama' => $detail->nama_item,
                    'harga' => (int) $detail->harga,
                    'jumlah' => (int) $detail->jumlah,
                    'subtotal' => (int) $detail->subtotal,
                ];
            })->values()->all(),
            'total' => (int) $dataPesanan->total,
            'metode' => str_replace(
                'Metode pembayaran: ',
                '',
                $dataPesanan->catatan ?? ''
            ),
            'waktu' => $dataPesanan->created_at
                ? $dataPesanan->created_at->format('d-m-Y H:i')
                : '-',
            'status' => $dataPesanan->status_pembayaran,
        ];
    }
}
