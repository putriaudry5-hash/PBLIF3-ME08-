<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Support\KantinMenu;

class MenuTenantController extends Controller
{
    public function index($tenant)
    {
        $tenantId = (int) $tenant;
        $daftarTenant = KantinMenu::tenants();

        if (!isset($daftarTenant[$tenantId])) {
            abort(404, 'Tenant tidak ditemukan.');
        }

        $semuaMenu = KantinMenu::menus();

        return view('pelanggan.menu-tenant', [
            'tenantId' => $tenantId,
            'tenant' => array_merge(
                $daftarTenant[$tenantId],
                ['warna' => '#74182B']
            ),
            'menus' => $semuaMenu[$tenantId] ?? [],
        ]);
    }
}
