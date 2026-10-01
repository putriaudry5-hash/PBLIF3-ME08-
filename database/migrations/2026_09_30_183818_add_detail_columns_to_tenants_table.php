<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('nama_penanggung_jawab')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('jenis_tenant')->default('satuan');
            $table->string('lokasi_kios')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'nama_penanggung_jawab',
                'no_hp',
                'jenis_tenant',
                'lokasi_kios',
            ]);
        });
    }
};