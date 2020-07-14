<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateMapMarkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_markers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->text('data');
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('map_marker_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('marker_id')->unsigned();
            $table->string('locale');
            $table->string('name');
            $table->string('description')->nullable();

            $table->foreign('marker_id')->references('id')->on('map_markers')->onDelete('cascade');

            $table->unique(['marker_id', 'locale']);
            $table->index(['marker_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_marker_translations');
        Schema::dropIfExists('map_markers');
    }
}
