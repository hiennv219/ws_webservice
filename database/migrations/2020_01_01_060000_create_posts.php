<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Katniss\Everdeen\Vendors\Laravel\Framework\Illuminate\Database\Schema\Blueprint;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('viewed')->unsigned()->default(0);
            $table->string('template')->nullable();
            $table->string('featured_image')->nullable();
            $table->tinyInteger('status')->unsigned()->default(1); // 1 = PUBLISHED;
            $table->tinyInteger('type')->unsigned()->default(0); // 0 = PAGE;
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user_id', 'status', 'type', 'created_at']);
        });

        Schema::create('post_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('post_id')->unsigned();
            $table->string('locale');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->longText('content');
            $table->longText('raw_content');

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->unique(['post_id', 'locale']);
            $table->index(['post_id', 'locale', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
    }
}
