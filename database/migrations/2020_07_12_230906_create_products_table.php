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
            $table->integer('category_id')->unsigned();
            $table->string('name');
            $table->string('generic')->nullable();
            $table->string('type')->nullable();
            $table->string('manufactured')->nullable();
            $table->string('picture')->default('product.jpg');
            $table->string('size')->nullable();
            $table->string('quantity')->nullable();
            $table->string('pieces_per_pata')->nullable();
            $table->string('dose')->nullable();
            $table->string('old_mrp')->nullable();
            $table->string('mrp')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
