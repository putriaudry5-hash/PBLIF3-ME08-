<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    // Menampilkan daftar menu dari database.
    public function index(Request $request)
    {
        $query = DB::table('menus')
            ->join('tenants', 'menus.tenant_id', '=', 'tenants.id')
            ->select(
                'menus.id',
                'menus.tenant_id',
                'tenants.nama as nama_tenant',
                'tenants.jenis as jenis_tenant',
                'menus.nama_menu',
                'menus.jenis_menu',
                'menus.tipe_harga',
                'menus.harga',
                'menus.opsi_harga',
                'menus.foto',
                'menus.status',
                'menus.stok',
                'menus.aktif_online'
            )
            ->where('tenants.status', 'Aktif')
            ->where('menus.aktif_online', 1);

        if ($request->filled('tenant_id')) {
            $query->where(
                'menus.tenant_id',
                $request->integer('tenant_id')
            );
        }

        return response()->json(
            $query->orderBy('menus.id')->get()
        );
    }

    // Menampilkan detail satu menu.
    public function show(string $id)
    {
        $menu = DB::table('menus')
            ->join('tenants', 'menus.tenant_id', '=', 'tenants.id')
            ->select(
                'menus.id',
                'menus.tenant_id',
                'tenants.nama as nama_tenant',
                'tenants.jenis as jenis_tenant',
                'menus.nama_menu',
                'menus.jenis_menu',
                'menus.tipe_harga',
                'menus.harga',
                'menus.opsi_harga',
                'menus.foto',
                'menus.status',
                'menus.stok',
                'menus.aktif_online'
            )
            ->where('menus.id', $id)
            ->where('tenants.status', 'Aktif')
            ->where('menus.aktif_online', 1)
            ->first();

        if (!$menu) {
            return response()->json([
                'message' => 'Menu tidak ditemukan atau sedang tidak tersedia.'
            ], 404);
        }

        return response()->json($menu);
    }
}