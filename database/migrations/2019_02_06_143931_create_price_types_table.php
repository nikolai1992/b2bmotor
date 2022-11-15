<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceTypesTable extends Migration
{
    public function up(): void
    {
        Schema::create('price_types', function (Blueprint $table) {
            $table->string('id');
            $table->string('uuid');
            $table->string('title');

            $table->primary(['id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_types');
    }
}
