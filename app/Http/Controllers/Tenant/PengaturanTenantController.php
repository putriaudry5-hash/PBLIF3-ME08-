<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\InternalUser;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PengaturanTenantController extends Controller
{
    public function index()
    {
        $user = InternalUser::findOrFail(
            Auth::guard('internal')->id()
        );

        $tenant = Tenant::findOrFail(
            $user->tenant_id
        );

        return view('tenant.pengaturan', compact(
            'tenant',
            'user'
        ));
    }

    public function updateProfil(Request $request)
    {
        $user = InternalUser::findOrFail(
            Auth::guard('internal')->id()
        );

        $tenant = Tenant::findOrFail(
            $user->tenant_id
        );

        $request->validate([
            'nama_tenant' => ['required', 'string', 'max:150'],
            'nama_penanggung_jawab' => ['nullable', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('tenants', 'email')->ignore($tenant->id),
            ],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'jenis_tenant' => ['required', 'string', 'max:50'],
            'lokasi_kios' => ['nullable', 'string', 'max:100'],
        ]);

        $tenant->update([
            'nama_tenant' => $request->nama_tenant,
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_tenant' => $request->jenis_tenant,
            'lokasi_kios' => $request->lokasi_kios,
        ]);

        return back()->with(
            'success',
            'Profil tenant berhasil diperbarui.'
        );
    }

    public function updateAkun(Request $request)
    {
        $user = InternalUser::findOrFail(
            Auth::guard('internal')->id()
        );

        $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'email_login' => [
                'required',
                'email',
                'max:150',
                Rule::unique('internal_users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email_login,
        ]);

        return back()->with(
            'success',
            'Akun login berhasil diperbarui.'
        );
    }

    public function updateOnline()
    {
        $user = InternalUser::findOrFail(
            Auth::guard('internal')->id()
        );

        $tenant = Tenant::findOrFail(
            $user->tenant_id
        );

        $tenant->update([
            'aktif_online' => !$tenant->aktif_online,
        ]);

        $pesan = $tenant->aktif_online
            ? 'Pesanan online tenant berhasil diaktifkan.'
            : 'Pesanan online tenant berhasil dinonaktifkan.';

        return back()->with(
            'success',
            $pesan
        );
    }

    public function updatePassword(Request $request)
    {
        $user = InternalUser::findOrFail(
            Auth::guard('internal')->id()
        );

        $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        if (!Hash::check(
            $request->password_lama,
            $user->password
        )) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->update([
            'password' => Hash::make(
                $request->password_baru
            ),
        ]);

        return back()->with(
            'success',
            'Password berhasil diperbarui.'
        );
    }
}