<?php

namespace App\Indicators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SingleParameter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'law', 'year', 'indicator', 'establishment_id', 'type', 'description',
        'month', 'position', 'value'
    ];

    public function establishment() {
        return $this->belongsTo('App\Establishment');
    }

    public function scopeSearch($query, Request $request) {
        if($request->input('law') != "") {
            $query->where('law', $request->input('law') );
        }

        if($request->input('year') != "") {
            $query->where('year', $request->input('year') );
        }

        if($request->input('indicator') != "") {
            $query->where('indicator', $request->input('indicator') );
        }

        if($request->input('establishment_id') != "") {
            $query->where('establishment_id', $request->input('establishment_id') );
        }

        return $query;
    }

    protected $table = 'ind_single_parameters';
}
