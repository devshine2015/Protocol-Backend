<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_categories', function (Blueprint $table) {
            $table->integer('id')->primary('relations_pkey');
            $table->string('name', 100);
            $table->boolean('is_active')->nullable()->default(1);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->smallInteger('status')->nullable()->default(0);
            $table->smallInteger('privacy')->nullable()->default(0);
            $table->smallInteger('type')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_categories');
    }
}
