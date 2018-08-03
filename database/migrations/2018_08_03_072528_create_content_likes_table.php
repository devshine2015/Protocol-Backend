<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('type')->comment("0=>bridge,1=>note,2=>element");
            $table->integer('type_id')->nullable();
            $table->string('emoji_type')->nullable();
            $table->foreign('user_id', 'content_likes_user_id_fkey')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_likes');
    }
}
