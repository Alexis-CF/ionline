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
        Schema::table('sum_types', function (Blueprint $table) {
            $table->foreignId('establishment_id')->after('name')->default(38)->constrained('establishments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sum_types', function (Blueprint $table) {
            $table->dropConstrainedForeignId('establishment_id');
        });
    }
};