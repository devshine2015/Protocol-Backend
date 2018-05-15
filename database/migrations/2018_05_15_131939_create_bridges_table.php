<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBridgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bridges', function(Blueprint $table)
		{
			$table->integer('id')->primary('bridges_pkey');
			$table->integer('from');
			$table->integer('to');
			$table->smallInteger('relation');
			$table->string('tags', 300)->nullable()->default('');
			$table->text('desc')->nullable()->default('');
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->smallInteger('status')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bridges');
	}

}
