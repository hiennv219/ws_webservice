<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateCategoriesPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer('category_id')->unsigned();
            $table->bigInteger('post_id')->unsigned();
            $table->integer('order')->unsigned()->default(0);

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->primary(['category_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories_posts');
    }
}
