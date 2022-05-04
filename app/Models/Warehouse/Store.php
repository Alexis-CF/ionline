<?php

namespace App\Models\Warehouse;

use App\Models\Commune;
use App\Models\Warehouse\Origin;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wre_stores';

    protected $fillable = [
        'name',
        'address',
        'commune_id'
    ];

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function origins()
    {
        return $this->hasMany(Origin::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'wre_store_user')
            ->using(StoreUser::class)
            ->withPivot(['role_id', 'status'])
            ->withTimestamps();
    }
}
