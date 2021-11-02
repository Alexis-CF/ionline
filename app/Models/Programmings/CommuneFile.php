<?php

namespace App\Models\Programmings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommuneFile extends Model
{
    use HasFactory;

    protected $table = 'pro_commune_files';

    protected $fillable = [
        'id','year', 'description', 'access', 'file_a', 'file_b','file_c','observation','status', 'user_id', 'commune_id'
    ];

    protected $casts = [
        'access' => 'array'
    ];

    public function commune() {
        return $this->belongsTo('\App\Models\Commune');
    }

    public function user() {
        return $this->belongsTo('\App\User');
    }

    public function programming_reviews() {
        return $this->hasMany('App\Models\Programmings\Review');
    }

}
