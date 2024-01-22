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
        Schema::table('rst_applicants', function (Blueprint $table) {
            $table->foreignId('approval_id')->after('technical_evaluation_id')->nullable()->constrained('sign_approvals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rst_applicants', function (Blueprint $table) {
            $table->dropForeign(['approval_id']);
            $table->dropColumn('approval_id');
        });
    }
};
