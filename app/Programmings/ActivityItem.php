<?php

namespace App\Programmings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityItem extends Model
{
    use SoftDeletes;
    protected $table = 'pro_activity_items';
    protected $fillable = [
        'id', 'int_code', 'vitakl_cycle', 'tracer', 'action_type', 'activity_name', 'def_target_population', 'verification_rem', 'professional', 'activity_id', 'cods', 'cols'
    ];

    public function program(){
        return $this->belongsTo('App\Programmings\ActivityProgram', 'activity_id');
    }

    public function programItems(){
        return $this->hasMany('App\Programmings\ProgrammingItem')->orderBy('activity_id', 'ASC');
    }

    public function programming(){
        return $this->belongsToMany(Programming::class, 'pro_programming_activity_item')->withPivot('requested_by', 'observation')->whereNull('pro_programming_activity_item.deleted_at')->withTimestamps()->using(ProgrammingActivityItem::class);
    }
}
