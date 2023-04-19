<?php

namespace App\Models\Agreements;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    public function agreements() {
        return $this->hasMany('App\Models\Agreements\Agreement');
    }

    public function resolutions() {
        return $this->hasMany('App\Models\Agreements\ProgramResolution')->orderBy('created_at','desc');
    }

    /**
     * Get the ProgramComponent for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function components()
    {
        return $this->hasMany('App\Models\Agreements\ProgramComponent');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'agr_programs';
}
