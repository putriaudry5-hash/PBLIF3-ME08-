<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalUser;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    public function index()
    {
        $admin = $this->getAdmin();

        if (!$admin) {
            return redirect()
                ->route('login.internal')
                ->with('login_error', 'Silakan login sebagai Admin.');
        }

        $setting = Setting::firstOrCreate(
            ['id' => 1],
            ['nama_kantin' => 'KantinKita']
        );

        return view('admin.pengaturan', compact(
            'setting',
            'admin'
        ));
    }

    public function update(Request $request)
    {
        $admin = $this->getAdmin();

        if (!$admin) {
            return redirect()->route('login.internal');
        }

        $request->validate([
            'nama_kantin' => [
                'required',
                'string',
                'max:100'
            ],
            'jam_buka' => [
                'nullable',
                'date_format:H:i'
            ],
            'jam_tutup' => [
                'nullable',
                'date_format:H:i'
            ],
            'nama_admin' => [
                'required',
                'string',
                'max:100'
            ],
            'email_admin' => [
                'required',
                'email',
                'max:150',
                Rule::unique(
                    'internal_users',
                    'email'
                )->ignore($admin->id)
            ],
        ]);

        $jamBuka = $request->input('jam_buka');
        $jamTutup = $request->input('jam_tutup');

        if (
            $jamBuka &&
            $jamTutup &&
            $jamTutup <= $jamBuka
        ) {
            return back()
                ->withErrors([
                    'jam_tutup' =>
                        'Jam tutup harus lebih besar dari jam buka.'
                ])
                ->withInput();
        }

        $setting = Setting::firstOrCreate(['id' => 1]);

        $setting->update([
            'nama_kantin' => $request->input('nama_kantin'),
            'jam_buka' => $jamBuka,
            'jam_tutup' => $jamTutup,
        ]);

        $admin->update([
            'nama' => $request->input('nama_admin'),
            'email' => $request->input('email_admin'),
        ]);

        return redirect()
            ->route('admin.pengaturan')
            ->with(
                'success',
                'Pengaturan berhasil disimpan.'
            );
    }

    public function updatePassword(Request $request)
    {
        $admin = $this->getAdmin();

        if (!$admin) {
            return redirect()->route('login.internal');
        }

        $request->validate([
            'password_lama' => [
                'required',
                'string'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed'
            ],
        ]);

        $passwordLama = $request->input('password_lama');
        $passwordBaru = $request->input('password');

        if (!Hash::check(
            $passwordLama,
            $admin->password
        )) {
            return back()->withErrors([
                'password_lama' =>
                    'Password lama tidak sesuai.'
            ]);
        }

        if (Hash::check(
            $passwordBaru,
            $admin->password
        )) {
            return back()->withErrors([
                'password' =>
                    'Password baru tidak boleh sama dengan password lama.'
            ]);
        }

        $admin->update([
            'password' => Hash::make($passwordBaru)
        ]);

        return redirect()
            ->route('admin.pengaturan')
            ->with(
                'success_password',
                'Password berhasil diubah.'
            );
    }

    private function getAdmin(): ?InternalUser
    {
        $id = Auth::guard('internal')->id();

        if (!$id) {
            return null;
        }

        return InternalUser::where('id', $id)
            ->where('role', 'admin')
            ->first();
    }
}