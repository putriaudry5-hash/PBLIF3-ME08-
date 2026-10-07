<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->string('nama_menu');

            $table->enum('jenis_menu', [
                'satuan',
                'prasmanan'
            ]);

            $table->enum('tipe_harga', [
                'tetap',
                'fleksibel',
                'per_potong'
            ])->default('tetap');

            $table->unsignedBigInteger('harga')->nullable();

            // Contoh: [2000, 3000, 4000, 5000]
            $table->json('opsi_harga')->nullable();

            // Menu tertentu saja yang perlu stok
            $table->unsignedInteger('stok')->nullable();

            $table->string('foto')->nullable();

            $table->enum('status', [
                'tersedia',
                'habis'
            ])->default('tersedia');

            $table->boolean('aktif_online')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};