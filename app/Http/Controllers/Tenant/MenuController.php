<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
{
    $user = Auth::guard('internal')->user();

    $tenant = $user->tenant;

    if (!$tenant) {
        abort(404);
    }

    $menus = Menu::where('tenant_id', $user->tenant_id)
        ->latest()
        ->get();

    $menuSatuan = $menus->where('jenis_menu', 'satuan');
    $menuPrasmanan = $menus->where('jenis_menu', 'prasmanan');

    return view('tenant.menu.index', compact(
        'user',
        'tenant',
        'menus',
        'menuSatuan',
        'menuPrasmanan'
    ));
}

    public function store(Request $request)
    {
        $tenantId = Auth::guard('internal')->user()->tenant_id;

        $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],

            'jenis_menu' => [
                'required',
                Rule::in(['satuan', 'prasmanan']),
            ],

            'tipe_harga' => [
                'required',
                Rule::in(['tetap', 'fleksibel', 'per_potong']),
            ],

            'harga' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'opsi_harga' => [
                'nullable',
                'array',
            ],

            'opsi_harga.*' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'stok' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if (
            $request->tipe_harga === 'tetap' ||
            $request->tipe_harga === 'per_potong'
        ) {
            $request->validate([
                'harga' => ['required', 'integer', 'min:1'],
            ]);
        }

        if ($request->tipe_harga === 'fleksibel') {
            $opsiHarga = collect($request->opsi_harga ?? [])
                ->filter(fn ($harga) => $harga !== null && $harga !== '')
                ->map(fn ($harga) => (int) $harga)
                ->values()
                ->all();

            if (count($opsiHarga) === 0) {
                return back()
                    ->withErrors([
                        'opsi_harga' => 'Masukkan minimal satu pilihan harga prasmanan.',
                    ])
                    ->withInput();
            }
        } else {
            $opsiHarga = null;
        }

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store(
                'menus',
                'public'
            );
        }

        $stok = $request->jenis_menu === 'satuan'
            ? $request->stok
            : null;

        Menu::create([
            'tenant_id' => $tenantId,
            'nama_menu' => $request->nama_menu,
            'jenis_menu' => $request->jenis_menu,
            'tipe_harga' => $request->tipe_harga,
            'harga' => $request->tipe_harga === 'fleksibel'
                ? null
                : $request->harga,
            'opsi_harga' => $opsiHarga,
            'stok' => $stok,
            'foto' => $foto,
            'status' => 'tersedia',
            'aktif_online' => true,
        ]);

        return back()->with(
            'success',
            'Menu berhasil ditambahkan.'
        );
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::guard('internal')->user()->tenant_id;

        $menu = Menu::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],

            'jenis_menu' => [
                'required',
                Rule::in(['satuan', 'prasmanan']),
            ],

            'tipe_harga' => [
                'required',
                Rule::in(['tetap', 'fleksibel', 'per_potong']),
            ],

            'harga' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'opsi_harga' => [
                'nullable',
                'array',
            ],

            'opsi_harga.*' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'stok' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if (
            $request->tipe_harga === 'tetap' ||
            $request->tipe_harga === 'per_potong'
        ) {
            $request->validate([
                'harga' => ['required', 'integer', 'min:1'],
            ]);
        }

        if ($request->tipe_harga === 'fleksibel') {
            $opsiHarga = collect($request->opsi_harga ?? [])
                ->filter(fn ($harga) => $harga !== null && $harga !== '')
                ->map(fn ($harga) => (int) $harga)
                ->values()
                ->all();

            if (count($opsiHarga) === 0) {
                return back()
                    ->withErrors([
                        'opsi_harga' => 'Masukkan minimal satu pilihan harga prasmanan.',
                    ])
                    ->withInput();
            }
        } else {
            $opsiHarga = null;
        }

        $foto = $menu->foto;

        if ($request->hasFile('foto')) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }

            $foto = $request->file('foto')->store(
                'menus',
                'public'
            );
        }

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'jenis_menu' => $request->jenis_menu,
            'tipe_harga' => $request->tipe_harga,
            'harga' => $request->tipe_harga === 'fleksibel'
                ? null
                : $request->harga,
            'opsi_harga' => $opsiHarga,
            'stok' => $request->jenis_menu === 'satuan'
                ? $request->stok
                : null,
            'foto' => $foto,
        ]);

        return back()->with(
            'success',
            'Menu berhasil diperbarui.'
        );
    }

    public function toggleStatus($id)
    {
        $tenantId = Auth::guard('internal')->user()->tenant_id;

        $menu = Menu::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $menu->update([
            'status' => $menu->status === 'tersedia'
                ? 'habis'
                : 'tersedia',
        ]);

        return back()->with(
            'success',
            'Status menu berhasil diperbarui.'
        );
    }

    public function toggleOnline($id)
    {
        $tenantId = Auth::guard('internal')->user()->tenant_id;

        $menu = Menu::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $menu->update([
            'aktif_online' => !$menu->aktif_online,
        ]);

        return back()->with(
            'success',
            'Status pesanan online berhasil diperbarui.'
        );
    }

    public function destroy($id)
    {
        $tenantId = Auth::guard('internal')->user()->tenant_id;

        $menu = Menu::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();

        return back()->with(
            'success',
            'Menu berhasil dihapus.'
        );
    }
}