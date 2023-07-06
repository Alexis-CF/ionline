<?php

namespace App\Models\HotelBooking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Hotel extends Model implements Auditable
{
    use HasFactory;
    use softDeletes;
    use \OwenIt\Auditing\Auditable;

    //
    protected $fillable = [
        'id','region_id','commune_id','name','address','description','latitude','longitude'
    ];

    protected $table = 'hb_hotels';

    // relaciones
    public function commune()
    {
        return $this->belongsTo('App\Models\ClCommune', 'commune_id');
    }
}
