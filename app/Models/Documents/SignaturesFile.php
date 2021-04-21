<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SignaturesFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'signature_id', 'file', 'file_type', 'signed_file',
    ];

    public function signaturesFlows()
    {
        return $this->hasMany('App\Models\Documents\SignaturesFlow', 'signatures_file_id');
    }

    public function signature(){
        return $this->belongsTo('App\Models\Documents\Signature', 'signature_id');
    }

    public function getHasSignedFlowAttribute()
    {
        return $this->signaturesFlows->where('status', 1)->count() > 0;
    }

    public function getHasAllFlowsSignedAttribute()
    {
        return $this->signaturesFlows->every('status', 1);
    }


    protected $table = 'doc_signatures_files';
}
