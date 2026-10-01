<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->nullOnDelete();
            $table->string('jenis')->default('offline');
            $table->string('sumber')->default('tenant');
            $table->string('metode_pembayaran')->default('cash');
            $table->unsignedBigInteger('total');
            $table->unsignedBigInteger('jumlah_bayar')->nullable();
            $table->unsignedBigInteger('kembalian')->default(0);
            $table->string('status')->default('lunas');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};