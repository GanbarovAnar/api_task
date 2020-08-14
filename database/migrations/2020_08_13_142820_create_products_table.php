<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger("external_id")->unique();

            $table->string("name"); // валидация 200
            $table->string("description")->nullable(); // валидация 1 000
            $table->timestamps();
            $table->double("price",15,2);
            $table->integer("quantity"); // количество
            $table->string("category_id"); // потому что тут строка, содержащая категории через запятую
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
