<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->boolean('available')->default(1);
            $table->string('url');
            $table->integer('price');
            $table->integer('oldprice')->nullable();
            $table->string('currency_id')->default('RUB');
            $table->string('category');
            $table->string('sub_category');
            $table->string('sub_sub_category');
            $table->string('picture')->nullable();
            $table->string('vendor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
