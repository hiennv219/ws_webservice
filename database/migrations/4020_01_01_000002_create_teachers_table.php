<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('user_id')->unsigned()->primary();
            $table->integer('viewed')->unsigned()->default(0);

            $table->bigInteger('approving_user_id')->unsigned()->nullable();
            $table->dateTime('approving_at')->nullable();

            $table->string('video_teaching_url')->nullable();
            $table->string('video_introduce_url')->nullable();
            $table->text('about_me')->nullable();
            $table->text('experience')->nullable();
            $table->longText('methodology')->nullable();

            $table->string('available_times')->nullable();
            $table->string('certificates')->nullable();
            $table->longText('payment_info')->nullable();

            $table->tinyInteger('teaching_status')->default(1);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approving_user_id')->references('id')->on('users');

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
