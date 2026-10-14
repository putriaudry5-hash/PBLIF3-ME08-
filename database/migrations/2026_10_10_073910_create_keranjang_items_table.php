
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang_items', function (Blueprint $table) {
            $table->id();
            $table->string('cart_token', 64)->index();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedInteger('menu_index');
            $table->string('tenant_nama');
            $table->string('menu_nama');
            $table->string('gambar')->nullable();
            $table->unsignedInteger('harga');
            $table->unsignedInteger('jumlah')->default(1);
            $table->timestamps();

            $table->unique(
                ['cart_token', 'tenant_id', 'menu_index'],
                'keranjang_item_unik'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang_items');
    }
};