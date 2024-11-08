<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndHealthGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_health_goals', function (Blueprint $table) {
            $table->id();
            $table->string('law')->nullable();
            $table->string('name');
            $table->integer('year');
            $table->enum('status',['development', 'review', 'verified'])->default('development');
            $table->timestamps();
            $table->tinyInteger('number');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ind_health_goals');
    }
}
