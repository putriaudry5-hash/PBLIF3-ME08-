<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminSettingsController extends Controller
{
    private function nameColumn(): string
    {
        return Schema::hasColumn('users', 'name') ? 'name' : 'nama';
    }

    private function setting(string $key, string $default = ''): string
    {
        return (string) (DB::table('application_settings')
            ->where('key', $key)
            ->value('value') ?? $default);
    }

    private function saveSetting(string $key, ?string $value): void
    {
        DB::table('application_settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value ?? '', 'updated_at' => now()]
        );
    }

    private function settingsData(): array
    {
        $admin = Auth::guard('web')->user();
        $nameColumn = $this->nameColumn();

        return [
            'namaKantin' => $this->setting('nama_kantin', 'KantinKita'),
            'jamBuka' => $this->setting('jam_buka'),
            'jamTutup' => $this->setting('jam_tutup'),
            'namaAdmin' => $admin->{$nameColumn},
            'emailAdmin' => $admin->email
        ];
    }

    public function index()
    {
        return response()->json([
            'data' => $this->settingsData()
        ]);
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('web')->user();

        $validated = $request->validate([
            'namaKantin' => ['required', 'string', 'max:100'],
            'jamBuka' => ['nullable', 'date_format:H:i'],
            'jamTutup' => ['nullable', 'date_format:H:i'],
            'namaAdmin' => ['required', 'string', 'max:255'],
            'emailAdmin' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id)
            ]
        ]);

        DB::transaction(function () use ($validated, $admin) {
            $this->saveSetting('nama_kantin', $validated['namaKantin']);
            $this->saveSetting('jam_buka', $validated['jamBuka'] ?? '');
            $this->saveSetting('jam_tutup', $validated['jamTutup'] ?? '');

            DB::table('users')
                ->where('id', $admin->id)
                ->update([
                    $this->nameColumn() => $validated['namaAdmin'],
                    'email' => $validated['emailAdmin'],
                    'updated_at' => now()
                ]);
        });

        // Muat ulang data Admin setelah diperbarui.
        Auth::guard('web')->setUser(
            \App\Models\User::findOrFail($admin->id)
        );

        return response()->json([
            'message' => 'Pengaturan berhasil disimpan.',
            'data' => $this->settingsData()
        ]);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => [
                'required', 'string', 'min:8',
                'confirmed', 'different:password_lama'
            ]
        ]);

        $admin = Auth::guard('web')->user();

        if (!Hash::check($validated['password_lama'], $admin->password)) {
            throw ValidationException::withMessages([
                'password_lama' => [
                    'Password lama yang dimasukkan salah.'
                ]
            ]);
        }

        DB::table('users')
            ->where('id', $admin->id)
            ->update([
                'password' => Hash::make($validated['password_baru']),
                'updated_at' => now()
            ]);

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Password berhasil diubah.'
        ]);
    }
}
