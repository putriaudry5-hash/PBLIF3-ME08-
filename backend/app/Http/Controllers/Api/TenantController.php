<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    // Menampilkan seluruh tenant
    public function index()
    {
        return response()->json(
            Tenant::orderBy('id', 'asc')->get()
        );
    }

    // Menambahkan tenant baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'pj' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:tenants,email',
            'telepon' => ['required', 'regex:/^08[0-9]{8,11}$/'],
            'jenis' => [
                'required',
                Rule::in([
                    'Menu Satuan',
                    'Prasmanan',
                    'Menu Satuan & Prasmanan'
                ])
            ],
            'lokasi' => 'required|string|max:100'
        ]);

        $tenant = Tenant::create([
            ...$data,
            'status' => 'Aktif'
        ]);

        return response()->json([
            'message' => 'Tenant berhasil ditambahkan.',
            'data' => $tenant
        ], 201);
    }

    // Menampilkan detail tenant
    public function show(string $id)
    {
        $tenant = Tenant::findOrFail($id);

        return response()->json($tenant);
    }

    // Mengedit tenant
    public function update(Request $request, string $id)
    {
        $tenant = Tenant::findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'pj' => 'required|string|max:150',
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('tenants', 'email')->ignore($tenant->id)
            ],
            'telepon' => ['required', 'regex:/^08[0-9]{8,11}$/'],
            'jenis' => [
                'required',
                Rule::in([
                    'Menu Satuan',
                    'Prasmanan',
                    'Menu Satuan & Prasmanan'
                ])
            ],
            'lokasi' => 'required|string|max:100'
        ]);

        $tenant->update($data);

        return response()->json([
            'message' => 'Data tenant berhasil diperbarui.',
            'data' => $tenant->fresh()
        ]);
    }

    // Mengaktifkan atau menonaktifkan tenant
    public function toggleStatus(string $id)
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->update([
            'status' => $tenant->status === 'Aktif'
                ? 'Nonaktif'
                : 'Aktif'
        ]);

        return response()->json([
            'message' => 'Status tenant berhasil diperbarui.',
            'data' => $tenant->fresh()
        ]);
    }

    // Menghapus tenant
    public function destroy(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return response()->json([
            'message' => 'Tenant berhasil dihapus.'
        ]);
    }
}
