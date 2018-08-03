<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('type')->comment("0=>bridge,1=>note,2=>element");
            $table->integer('type_id')->nullable();
            $table->foreign('user_id', 'content_likes_user_id_fkey')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->string('report')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('content_reports');
    }
}
