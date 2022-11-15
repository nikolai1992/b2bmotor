<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->integer('tag_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('price_type_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_prices');
    }
}
