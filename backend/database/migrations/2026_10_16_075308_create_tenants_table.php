
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('pj', 150);
            $table->string('email', 150)->unique();
            $table->string('telepon', 20);
            $table->string('jenis', 100);
            $table->string('lokasi', 100);
            $table->enum('status', ['Aktif', 'Nonaktif'])
                  ->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
