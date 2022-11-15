<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('price_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id');
            $table->string('price_type_id');
            $table->float('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_products');
    }
}

