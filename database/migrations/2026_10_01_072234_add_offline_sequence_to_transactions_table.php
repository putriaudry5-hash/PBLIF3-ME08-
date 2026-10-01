<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Nomor urut transaksi tenant hari itu
            // Contoh: 1, 2, 3, dst
            $table->unsignedInteger('nomor_urut')
                ->nullable()
                ->after('kode_transaksi');

            // Supaya besok kode 4.1 boleh dipakai lagi
            $table->date('tanggal_transaksi')
                ->nullable()
                ->after('nomor_urut');

            // Hapus unique lama:
            // kode_transaksi tidak boleh unique global
            $table->dropUnique(['kode_transaksi']);

            // Tetapi kode harus unique dalam satu hari
            $table->unique([
                'tanggal_transaksi',
                'kode_transaksi'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropUnique([
                'tanggal_transaksi',
                'kode_transaksi'
            ]);

            $table->dropColumn([
                'nomor_urut',
                'tanggal_transaksi'
            ]);

            $table->unique('kode_transaksi');
        });
    }
};