<?php

namespace App\Models\Parameters;

use App\Models\Establishment;
use App\Models\Inv\Inventory;
use App\Models\Inv\inventoryMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Place extends Model
{
    use SoftDeletes;

    protected $table = 'cfg_places';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'architectural_design_code',
        'location_id',
        'establishment_id',
        'floor_number',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function computers()
    {
        return $this->hasMany('App\Models\Resources\Computer');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'place_id');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'place_id');
    }

    public function getQrAttribute() {
        return QrCode::size(150)
            ->generate(route('parameters.places.board', [
                'establishment' => $this->establishment_id,
                'place' => $this->id
            ]));
    }

    public function getQrSmallAttribute() {
        return QrCode::size(74)
            ->generate(route('parameters.places.board', [
                'establishment' => $this->establishment_id,
                'place' => $this->id
            ]));
    }


}
