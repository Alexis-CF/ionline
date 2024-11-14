<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_actions', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->text('name');
            $table->text('verification_means');
            $table->enum('target_type',['de mantención','de disminución de la brecha'])->nullable();
            $table->boolean('is_accum')->nullable();
            $table->string('numerator')->nullable();
            $table->string('numerator_source')->nullable();
            $table->text('numerator_cods')->nullable();
            $table->text('numerator_cols')->nullable();
            $table->string('denominator')->nullable();
            $table->string('denominator_source')->nullable();
            $table->text('denominator_cods')->nullable();
            $table->text('denominator_cols')->nullable();
            $table->decimal('weighting', 6, 3)->nullable(); // Ponderación al corte de la acción
            $table->foreignId('section_id')->constrained('ind_sections');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ind_actions');
    }
}
