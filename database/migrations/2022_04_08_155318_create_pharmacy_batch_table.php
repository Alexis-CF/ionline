<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmacyBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frm_batchs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('frm_products');
            $table->dateTime('due_date'); //fecha vencimiento
            $table->string('batch');
            $table->integer('count');
            $table->timestamps();
            $table->softDeletes();
        });

        //Utilizado
        /*
        Schema::table('frm_purchases_items', function (Blueprint $table) {
          // $table->unsignedBigInteger('batch_id')->after('batch')->nullable();
          $table->foreignId('batch_id')->after('batch')->nullable();
          $table->foreign('batch_id')->references('id')->on('frm_batchs');
        });
        */
        //Utilizado
        /*
        Schema::table('frm_receiving_items', function (Blueprint $table) {
          // $table->unsignedBigInteger('batch_id')->after('batch')->nullable();
          $table->foreignId('batch_id')->after('batch')->nullable();
          $table->foreign('batch_id')->references('id')->on('frm_batchs');
        });
        */

        //Utilizado
        /*
        Schema::table('frm_dispatch_items', function (Blueprint $table) {
          // $table->unsignedBigInteger('batch_id')->after('batch')->nullable();
          $table->foreignId('batch_id')->after('batch')->nullable();
          $table->foreign('batch_id')->references('id')->on('frm_batchs');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        /*
        Schema::table('frm_purchases_items', function (Blueprint $table){
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
        */
        /*
        Schema::table('frm_receiving_items', function (Blueprint $table){
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
        */
        /*
        Schema::table('frm_dispatch_items', function (Blueprint $table){
            $table->dropForeign(['batch_id']);
            $table->dropColumn('batch_id');
        });
        */
        Schema::dropIfExists('frm_batchs');
    }
}
