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
        Schema::create('inv_inventory_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_id')
            ->nullable()
            ->constrained('inv_inventories');

            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users');

            $table->timestamps();
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_inventory_user');
    }
};
