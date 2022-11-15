<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('category_products', function (Blueprint $table) {
            $table->string('category_1c_id');
            $table->string('product_1c_id');

            $table->primary(['category_1c_id', 'product_1c_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_products');
    }
}
