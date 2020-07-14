<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('locale')->default('en');
            $table->string('country')->default('US');
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->string('number_format')->default('point_comma');
            $table->tinyInteger('first_day_of_week')->unsigned()->default(0);
            $table->tinyInteger('long_date_format')->unsigned()->default(0);
            $table->tinyInteger('short_date_format')->unsigned()->default(0);
            $table->tinyInteger('long_time_format')->unsigned()->default(0);
            $table->tinyInteger('short_time_format')->unsigned()->default(0);
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('display_name');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('url_avatar');
            $table->string('url_avatar_thumb');

            $table->string('gender', 10)->nullable();
            $table->string('skype_id')->nullable();
            $table->string('facebook')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('nationality')->nullable();

            $table->string('activation_code')->default('');
            $table->boolean('active')->default(false);
            $table->bigInteger('setting_id')->unsigned();
            $table->string('channel')->default('');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('user_settings');

            $table->index(['setting_id', 'created_at']);
        });

        Schema::create('user_socials', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('user_id')->unsigned();
            $table->string('provider');
            $table->string('provider_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['provider', 'provider_id']);
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
        Schema::dropIfExists('user_socials');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_settings');
    }
}
