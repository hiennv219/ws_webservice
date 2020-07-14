<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('order')->unsigned()->default(0);
            $table->tinyInteger('type')->unsigned()->default(0);
            $table->timestamps();

            $table->index(['order', 'type', 'created_at']);
        });

        Schema::create('meta_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('meta_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreign('meta_id')->references('id')->on('meta')->onDelete('cascade');

            $table->unique(['meta_id', 'locale']);
            $table->index(['meta_id', 'locale', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_translations');
        Schema::dropIfExists('meta');
    }
}
