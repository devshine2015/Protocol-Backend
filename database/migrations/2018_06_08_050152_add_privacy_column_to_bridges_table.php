<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivacyColumnToBridgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bridges', function (Blueprint $table) {
            // 0: public
            // 1: only me
            $table->smallInteger('privacy')->default(0);
            $table->index(['from', 'privacy', 'status'], 'bridges_idx_from_privacy_status');
            $table->index(['to', 'privacy', 'status'], 'bridges_idx_to_privacy_status');
            $table->index(['from', 'privacy', 'status', 'created_by'], 'bridges_idx_from_privacy_status_created_by');
            $table->index(['to', 'privacy', 'status', 'created_by'], 'bridges_idx_to_privacy_status_created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bridges', function (Blueprint $table) {
            $table->dropIndex('bridges_idx_from_privacy_status');
            $table->dropIndex('bridges_idx_to_privacy_status');
            $table->dropIndex('bridges_idx_from_privacy_status_created_by');
            $table->dropIndex('bridges_idx_to_privacy_status_created_by');
            $table->dropColumn('privacy');
        });
    }
}
