<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('image')->default('');
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('link_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('link_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->string('url');
            $table->text('description')->nullable();

            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');

            $table->unique(['link_id', 'locale']);
            $table->index(['link_id', 'locale', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_translations');
        Schema::dropIfExists('links');
    }
}
