<?php

namespace App\Models\Pharmacies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatch extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'date', 'pharmacy_id', 'establishment_id', 'notes','inventory_adjustment_id', 'user_id', 'receiver_id', 'sendC19', 'created_at'
  ];

  use SoftDeletes;

  protected $table = 'frm_dispatches';

  //relaciones
  public function pharmacy()
  {
    return $this->belongsTo('App\Models\Pharmacies\Pharmacy');
  }

  public function dispatchItems()
  {
    return $this->hasMany('App\Models\Pharmacies\DispatchItem');
  }

  public function establishment()
  {
    return $this->belongsTo('App\Models\Pharmacies\Establishment');
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User','user_id')->withTrashed();
  }

  public function receiver()
  {
    return $this->belongsTo('App\Models\User','receiver_id')->withTrashed();
  }

  public function files()
  {
    return $this->hasMany('App\Models\Pharmacies\File');
  }

  public function verificationMailings()
  {
    return $this->hasMany('App\Models\Pharmacies\DispatchVerificationMailing');
  }

  public function inventoryAdjustment()
  {
    return $this->belongsTo('App\Models\Pharmacies\InventoryAdjustment');
  }

  protected $dates = ['date'];
}
