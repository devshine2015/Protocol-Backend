<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultTypeTo0RelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relations', function (Blueprint $table) {
            $table->smallInteger('type')->default(0)->change();
            $table->index(['type', 'status'], 'relations_idx_type_status');
            $table->index(['type', 'created_by', 'status'], 'relations_idx_type_status_created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relations', function (Blueprint $table) {
            $table->dropIndex('relations_idx_type_status');
            $table->dropIndex('relations_idx_type_status_created_by');
            $table->smallInteger('type')->default(1)->change();
        });
    }
}
