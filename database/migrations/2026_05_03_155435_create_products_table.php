<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // relasi ke user (penjual)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // relasi ke kategori
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            // data produk
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock')->default(1);

            // kondisi barang
            $table->enum('condition', ['baru', 'bekas']);

            // status produk
            $table->enum('status', ['aktif', 'nonaktif', 'sold'])
                  ->default('aktif');

            $table->timestamps();

            // index (biar cepat)
            $table->index('user_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};