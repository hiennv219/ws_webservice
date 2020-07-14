<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateUserCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_certificates', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('type');
            $table->string('provided_by')->nullable();
            $table->dateTime('provided_at')->nullable();
            $table->string('image')->nullable();
            $table->string('meta')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_certificates');
    }
}
