<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Pharmacies\Dispatch;
use App\Pharmacies\Purchase;
use App\Pharmacies\Receiving;

class PharmacyDeleteItemsFromCab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('cab', function (Blueprint $table) {
        //     //
        // });
        $dispatchs = Dispatch::withTrashed()->whereNotNull('deleted_at')->get();
        foreach($dispatchs as $dispatch){
            foreach ($dispatch->dispatchItems as $key => $dispatchItem){
            $dispatchItem->delete();
            }
        }

        $purchases = Purchase::withTrashed()->whereNotNull('deleted_at')->get();
        foreach($purchases as $purchase){
            foreach ($purchase->purchaseItems as $key => $purchaseItem){
            $purchaseItem->delete();
            }
        }

        $receivings = Receiving::withTrashed()->whereNotNull('deleted_at')->get();
        foreach($receivings as $receiving){
            foreach ($receiving->receivingItems as $key => $receivingItem){
            $receivingItem->delete();
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cab', function (Blueprint $table) {
            //
        });
    }
}
