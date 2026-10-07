<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalUser;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            'nama_tenant' => ['required', 'string', 'max:255'],
            'nama_penanggung_jawab' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:tenants,email',
                'unique:internal_users,email',
            ],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_tenant' => ['required', 'in:satuan,prasmanan'],
            'lokasi_kios' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        DB::transaction(function () use ($request) {
            $password = Hash::make($request->password);

            $tenant = Tenant::create([
                'nama_tenant' => $request->nama_tenant,
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis_tenant' => $request->jenis_tenant,
                'lokasi_kios' => $request->lokasi_kios,
                'password' => $password,
                'status' => $request->status,
            ]);

            $user = new InternalUser();
            $user->tenant_id = $tenant->id;
            $user->nama = $tenant->nama_tenant;
            $user->email = $tenant->email;
            $user->password = $password;
            $user->role = 'tenant';
            $user->status = $tenant->status;
            $user->save();
        });

        return redirect()
            ->route('admin.tenant')
            ->with('success', 'Tenant dan akun login berhasil ditambahkan.');
    }

    public function update(Request $request, Tenant $tenant)
    {
        $internalUser = InternalUser::where('tenant_id', $tenant->id)
            ->where('role', 'tenant')
            ->first();

        $request->validate([
            'nama_tenant' => ['required', 'string', 'max:255'],
            'nama_penanggung_jawab' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tenants', 'email')->ignore($tenant->id),
                Rule::unique('internal_users', 'email')->ignore($internalUser?->id),
            ],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_tenant' => ['required', 'in:satuan,prasmanan'],
            'lokasi_kios' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $tenant, $internalUser) {
            $tenant->update([
                'nama_tenant' => $request->nama_tenant,
                'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis_tenant' => $request->jenis_tenant,
                'lokasi_kios' => $request->lokasi_kios,
            ]);

            if ($internalUser) {
                $internalUser->nama = $tenant->nama_tenant;
                $internalUser->email = $tenant->email;
                $internalUser->status = $tenant->status;
                $internalUser->save();
            }
        });

        return redirect()
            ->route('admin.tenant')
            ->with('success', 'Data tenant berhasil diperbarui.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        DB::transaction(function () use ($tenant) {
            $tenant->status = $tenant->status === 'aktif'
                ? 'nonaktif'
                : 'aktif';

            $tenant->save();

            $internalUser = InternalUser::where('tenant_id', $tenant->id)
                ->where('role', 'tenant')
                ->first();

            if ($internalUser) {
                $internalUser->status = $tenant->status;
                $internalUser->save();
            }
        });

        return redirect()
            ->route('admin.tenant')
            ->with('success', 'Status tenant berhasil diperbarui.');
    }
}