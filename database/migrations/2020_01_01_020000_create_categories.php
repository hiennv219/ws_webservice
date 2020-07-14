<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->tinyInteger('type')->unsigned()->default(0); // 0 = BLOG
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories');

            $table->index(['order', 'type', 'created_at']);
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unique(['category_id', 'locale']);
            $table->index(['category_id', 'locale', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
    }
}
