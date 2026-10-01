<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();

            $table->string('kode_pesanan')->unique();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('pelanggan_id')
                ->nullable()
                ->constrained('pelanggans')
                ->nullOnDelete();

            $table->unsignedBigInteger('total');

            $table->string('status_pembayaran')
                ->default('belum_lunas');

            $table->text('catatan')->nullable();

            $table->timestamp('dibayar_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};