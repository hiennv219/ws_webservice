<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateCategoriesLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_links', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('category_id')->unsigned();
            $table->integer('link_id')->unsigned();
            $table->integer('order')->unsigned()->default(0);

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');

            $table->primary(['category_id', 'link_id']);
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories_links');
    }
}
