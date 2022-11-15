<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->string('slug');
            $table->string('article')->nullable();
            $table->string('title');
            $table->string('short_title');
            $table->string('category_id')->default('');
            $table->tinyInteger('is_active')->default(1);
            $table->string('thumb')->default('images/no-image-icon.png');
            $table->timestamps();
            $table->string('	cat_page')->nullable();
            $table->unique(['uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
