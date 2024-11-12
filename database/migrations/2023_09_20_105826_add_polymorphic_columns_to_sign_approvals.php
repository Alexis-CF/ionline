<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sign_approvals', function (Blueprint $table) {
            $table->morphs('approvable')->after('reject_observation')->nullable();
            $table->foreignId('previous_approval_id')->after('reject_observation')->nullable()->constrained('sign_approvals')->onDelete('cascade');;
            $table->boolean('active')->after('reject_observation')->default(true);

            $table->index(['active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sign_approvals', function (Blueprint $table) {
            $table->dropIndex(['active']);
            $table->dropColumn('active');
            $table->dropForeign(['previous_approval_id']);
            $table->dropColumn('previous_approval_id');
            $table->dropMorphs('approvable');
        });
    }
};
