<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->string('kode')->unique();
            $table->enum('jenis', ['Offline', 'Online'])->default('Offline');
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', ['Menunggu Pembayaran', 'Lunas', 'Dibatalkan'])
                ->default('Menunggu Pembayaran');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('nama_item');
            $table->unsignedInteger('jumlah')->default(1);
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders');
            $table->enum('metode', ['Cash', 'Online'])->default('Cash');
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->decimal('uang_diterima', 12, 2)->nullable();
            $table->decimal('kembalian', 12, 2)->default(0);
            $table->enum('status', ['Menunggu', 'Lunas', 'Gagal'])
                ->default('Menunggu');
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
            $table->dropColumn([
                'metode', 'jumlah_bayar', 'uang_diterima',
                'kembalian', 'status', 'paid_at'
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
            $table->dropColumn([
                'nama_item', 'jumlah', 'harga', 'subtotal'
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropUnique(['kode']);
            $table->dropColumn(['kode', 'jenis', 'total', 'status']);
        });
    }
};