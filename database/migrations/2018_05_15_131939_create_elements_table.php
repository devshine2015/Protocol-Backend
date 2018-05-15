<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateElementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('elements', function(Blueprint $table)
		{
			$table->integer('id')->primary('elements_pkey');
			$table->smallInteger('type');
			$table->string('url', 2083)->index('elements_url_idx');
			$table->string('start_locator', 200)->nullable();
			$table->integer('start_offset')->nullable();
			$table->string('end_locator', 200)->nullable();
			$table->integer('end_offset')->nullable();
			$table->string('image', 200)->nullable();
			$table->text('text')->nullable();
			$table->string('rect', 100)->nullable();
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
		Schema::drop('elements');
	}

}
