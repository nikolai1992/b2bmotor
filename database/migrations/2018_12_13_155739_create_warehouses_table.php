<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('1c_id');
            $table->string('name');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->unique(['1c_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
}
