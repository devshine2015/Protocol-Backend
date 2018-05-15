<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBridgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bridges', function(Blueprint $table)
		{
			$table->foreign('"from"', 'bridges_from_fkey')->references('id')->on('elements')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('"to"', 'bridges_to_fkey')->references('id')->on('elements')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('relation', 'bridges_relation_fkey')->references('id')->on('relations')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bridges', function(Blueprint $table)
		{
			$table->dropForeign('bridges_from_fkey');
			$table->dropForeign('bridges_to_fkey');
			$table->dropForeign('bridges_relation_fkey');
		});
	}

}
