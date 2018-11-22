<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_type')->default(0);
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->text('message')->nullable();
            $table->string('criteria', 300)->nullable();
            $table->integer('message_categories_id')->nullable()->unsigned();
            $table->integer('message_criteria_id')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes();

             $table->foreign('message_criteria_id')->references('id')->on('message_criteria')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('message_categories_id')->references('id')->on('message_categories')->onDelete('RESTRICT')->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
