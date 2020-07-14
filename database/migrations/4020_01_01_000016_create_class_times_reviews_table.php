<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateClassTimesReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_times_reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('class_time_id')->unsigned();
            $table->bigInteger('review_id')->unsigned();

            $table->foreign('class_time_id')->references('id')->on('class_times')->onDelete('cascade');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');

            $table->primary(['class_time_id', 'review_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_times_reviews');
    }
}
