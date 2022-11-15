<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->string('product_1c_id');
            $table->string('warehouse_1c_id');
            $table->smallInteger('availability')->default(0);

            $table->primary(['product_1c_id', 'warehouse_1c_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_products');
    }
}
