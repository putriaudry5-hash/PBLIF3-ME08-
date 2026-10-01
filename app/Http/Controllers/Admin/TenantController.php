<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::orderBy('id', 'asc')->get();

        return view('admin.data-tenant', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tenant' => 'required|string|max:255',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'no_hp' => 'required|string|max:20',
            'jenis_tenant' => 'required|in:satuan,prasmanan',
            'lokasi_kios' => 'nullable|string|max:100',
            'password' => 'required|min:6|confirmed',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Tenant::create([
            'nama_tenant' => $request->nama_tenant,
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_tenant' => $request->jenis_tenant,
            'lokasi_kios' => $request->lokasi_kios,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.tenant')
            ->with('success', 'Tenant berhasil ditambahkan.');
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'nama_tenant' => 'required|string|max:255',
            'nama_penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email,' . $tenant->id,
            'no_hp' => 'required|string|max:20',
            'jenis_tenant' => 'required|in:satuan,prasmanan',
            'lokasi_kios' => 'nullable|string|max:100',
        ]);

        $tenant->update([
            'nama_tenant' => $request->nama_tenant,
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_tenant' => $request->jenis_tenant,
            'lokasi_kios' => $request->lokasi_kios,
        ]);

        return redirect()
            ->route('admin.tenant')
            ->with('success', 'Data tenant berhasil diperbarui.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->status = $tenant->status === 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $tenant->save();

        return redirect()
            ->route('admin.tenant')
            ->with('success', 'Status tenant berhasil diperbarui.');
    }
}