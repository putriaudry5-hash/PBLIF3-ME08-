<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    // Menampilkan isi keranjang pelanggan.
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = DB::table('carts')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart) {
            return response()->json([
                'items' => [],
                'total' => 0,
            ]);
        }

        $items = DB::table('cart_items')
            ->join('menus', 'cart_items.menu_id', '=', 'menus.id')
            ->join('tenants', 'cart_items.tenant_id', '=', 'tenants.id')
            ->where('cart_items.cart_id', $cart->id)
            ->select(
                'cart_items.id',
                'cart_items.menu_id',
                'cart_items.tenant_id',
                'cart_items.quantity',
                'cart_items.selected_price',
                'cart_items.jenis_harga',
                'menus.nama_menu',
                'menus.foto',
                'tenants.nama as nama_tenant'
            )
            ->orderBy('cart_items.id')
            ->get();

        $total = $items->sum(
            fn ($item) => (int) $item->selected_price * (int) $item->quantity
        );

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    // Menambahkan menu ke keranjang.
    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => 'required|integer|exists:tenants,id',
            'menu_id' => 'required|integer|exists:menus,id',
            'quantity' => 'sometimes|integer|min:1|max:99',
            'selected_price' => 'required|integer|min:1',
            'jenis_harga' => 'required|in:satuan,prasmanan',
        ]);

        $user = $request->user();

        if (($user->role ?? null) !== 'pelanggan') {
            return response()->json([
                'message' => 'Hanya pelanggan yang dapat menggunakan keranjang.',
            ], 403);
        }

        $quantity = $data['quantity'] ?? 1;

        return DB::transaction(function () use ($data, $user, $quantity) {
            $menu = DB::table('menus')
                ->where('id', $data['menu_id'])
                ->where('tenant_id', $data['tenant_id'])
                ->lockForUpdate()
                ->first();

            if (!$menu) {
                return response()->json([
                    'message' => 'Menu tidak ditemukan pada tenant tersebut.',
                ], 404);
            }

            $tenant = DB::table('tenants')
                ->where('id', $menu->tenant_id)
                ->first();

            if (!$tenant || $tenant->status !== 'Aktif') {
                return response()->json([
                    'message' => 'Tenant sedang tidak tersedia.',
                ], 422);
            }

            if ($menu->status !== 'tersedia' || !(bool) $menu->aktif_online) {
                return response()->json([
                    'message' => 'Menu sedang tidak tersedia.',
                ], 422);
            }

            $jenisTenant = strtolower(trim($tenant->jenis));
            $jenisDariDatabase = str_contains($jenisTenant, 'prasmanan')
                ? 'prasmanan'
                : 'satuan';

            if ($data['jenis_harga'] !== $jenisDariDatabase) {
                throw ValidationException::withMessages([
                    'jenis_harga' => [
                        'Jenis tenant tidak sesuai dengan menu yang dipilih.',
                    ],
                ]);
            }

            $tipeHarga = strtolower(trim($menu->tipe_harga));
            $hargaDipilih = (int) $data['selected_price'];
            $hargaDatabase = (int) $menu->harga;

            if (in_array($tipeHarga, ['tetap', 'potong', 'per_potong'], true)) {
                if ($hargaDipilih !== $hargaDatabase) {
                    throw ValidationException::withMessages([
                        'selected_price' => [
                            'Harga menu tidak sesuai dengan database.',
                        ],
                    ]);
                }
            } elseif (in_array(
                $tipeHarga,
                ['nominal', 'fleksibel', 'pilihan', 'pilihan_nominal'],
                true
            )) {
                $opsi = is_string($menu->opsi_harga)
                    ? json_decode($menu->opsi_harga, true)
                    : $menu->opsi_harga;

                if (!is_array($opsi) || empty($opsi)) {
                    throw ValidationException::withMessages([
                        'selected_price' => [
                            'Pilihan harga menu belum dikonfigurasi.',
                        ],
                    ]);
                }

                $hargaValid = collect($opsi)->contains(
                    function ($opsiHarga) use ($hargaDipilih) {
                        if (is_numeric($opsiHarga)) {
                            return (int) $opsiHarga === $hargaDipilih;
                        }

                        if (is_array($opsiHarga)) {
                            $harga = $opsiHarga['harga']
                                ?? $opsiHarga['price']
                                ?? $opsiHarga['nominal']
                                ?? null;

                            return $harga !== null
                                && (int) $harga === $hargaDipilih;
                        }

                        return false;
                    }
                );

                if (!$hargaValid) {
                    throw ValidationException::withMessages([
                        'selected_price' => [
                            'Nominal harga tersebut tidak tersedia.',
                        ],
                    ]);
                }
            } else {
                throw ValidationException::withMessages([
                    'selected_price' => [
                        'Tipe harga menu belum didukung.',
                    ],
                ]);
            }

            $cart = DB::table('carts')
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$cart) {
                $cartId = DB::table('carts')->insertGetId([
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $cartId = $cart->id;
            }

            $item = DB::table('cart_items')
                ->where('cart_id', $cartId)
                ->where('menu_id', $menu->id)
                ->where('selected_price', $hargaDipilih)
                ->where('jenis_harga', $data['jenis_harga'])
                ->lockForUpdate()
                ->first();

            if ($item) {
                $jumlahBaru = (int) $item->quantity + $quantity;

                if ($jumlahBaru > 99) {
                    throw ValidationException::withMessages([
                        'quantity' => [
                            'Jumlah menu dalam keranjang maksimal 99.',
                        ],
                    ]);
                }

                if ($jumlahBaru > (int) $menu->stok) {
                    throw ValidationException::withMessages([
                        'quantity' => [
                            'Jumlah dalam keranjang melebihi stok tersedia.',
                        ],
                    ]);
                }

                DB::table('cart_items')
                    ->where('id', $item->id)
                    ->update([
                        'quantity' => $jumlahBaru,
                        'updated_at' => now(),
                    ]);
            } else {
                if ($quantity > (int) $menu->stok) {
                    throw ValidationException::withMessages([
                        'quantity' => [
                            'Jumlah pesanan melebihi stok yang tersedia.',
                        ],
                    ]);
                }

                DB::table('cart_items')->insert([
                    'cart_id' => $cartId,
                    'menu_id' => $menu->id,
                    'tenant_id' => $menu->tenant_id,
                    'quantity' => $quantity,
                    'selected_price' => $hargaDipilih,
                    'jenis_harga' => $data['jenis_harga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'message' => 'Menu berhasil ditambahkan ke keranjang.',
            ], 201);
        });
    }

    // Mengubah jumlah item keranjang.
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $user = $request->user();

        if (($user->role ?? null) !== 'pelanggan') {
            return response()->json([
                'message' => 'Hanya pelanggan yang dapat menggunakan keranjang.',
            ], 403);
        }

        $item = DB::table('cart_items')
            ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
            ->where('cart_items.id', $id)
            ->where('carts.user_id', $user->id)
            ->select('cart_items.*')
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Item keranjang tidak ditemukan.',
            ], 404);
        }

        $menu = DB::table('menus')
            ->where('id', $item->menu_id)
            ->first();

        if (!$menu || $data['quantity'] > (int) $menu->stok) {
            return response()->json([
                'message' => 'Jumlah melebihi stok yang tersedia.',
            ], 422);
        }

        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'quantity' => $data['quantity'],
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Jumlah item berhasil diperbarui.',
        ]);
    }

    // Menghapus item keranjang.
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (($user->role ?? null) !== 'pelanggan') {
            return response()->json([
                'message' => 'Hanya pelanggan yang dapat menggunakan keranjang.',
            ], 403);
        }

        $item = DB::table('cart_items')
            ->join('carts', 'cart_items.cart_id', '=', 'carts.id')
            ->where('cart_items.id', $id)
            ->where('carts.user_id', $user->id)
            ->select('cart_items.id')
            ->first();

        if (!$item) {
            return response()->json([
                'message' => 'Item keranjang tidak ditemukan.',
            ], 404);
        }

        DB::table('cart_items')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus dari keranjang.',
        ]);
    }
}