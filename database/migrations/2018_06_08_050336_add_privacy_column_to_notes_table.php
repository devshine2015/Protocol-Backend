<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyColumnToNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // 0: public
            // 1: only me
            $table->smallInteger('privacy')->default(0);
            $table->index(['target', 'privacy', 'status'], 'notes_idx_target_privacy_status');
            $table->index(['target', 'privacy', 'status', 'created_by'], 'notes_idx_target_privacy_status_created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex('notes_idx_target_privacy_status');
            $table->dropIndex('notes_idx_target_privacy_status_created_by');
            $table->dropColumn('privacy');
        });
    }
}
