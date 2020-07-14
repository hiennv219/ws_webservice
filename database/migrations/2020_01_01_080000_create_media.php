<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('url');
            $table->tinyInteger('type')->unsigned()->default(0); // 1 = PHOTO;
            $table->timestamps();

            $table->index(['type', 'created_at']);
        });

        Schema::create('media_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('media_id')->unsigned();
            $table->string('locale');
            $table->string('title');
            $table->string('description')->nullable();

            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

            $table->unique(['media_id', 'locale']);
            $table->index(['media_id', 'locale', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_translations');
        Schema::dropIfExists('media');
    }
}
