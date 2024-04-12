<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\hasOne;
use App\Models\Documents\Parte;

class SignaturesFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'doc_signatures_files';

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

    public function document()
    {
        return $this->hasOne('App\Models\Documents\Document', 'file_to_sign_id');
    }

    public function suitabilityResult()
    {
        return $this->hasOne('App\Models\Suitability\Result', 'signed_certificate_id');
    }

    // public function parteFile()
    // {
    //     return $this->hasOne('App\Models\Documents\ParteFile', 'signature_file_id');
    // }

    public function parte(): hasOne
    {
        return $this->hasOne(Parte::class);
    }

    /**
     * @return bool
     */
    public function getHasSignedFlowAttribute()
    {
        return $this->signaturesFlows()->where('status', 1)->count() > 0;
    }

    public function getFileNameAttribute()
    {
        return basename($this->file);
    }

    public function getHasRejectedFlowAttribute()
    {
        return $this->signaturesFlows()->where('status', 0)->count() > 0;
    }

    /**
     * @return bool
     */
    public function getHasAllFlowsSignedAttribute()
    {
        return $this->signaturesFlows->every('status', 1);
    }

    public function getHasOnePendingFlowAttribute()
    {
        return $this->signaturesFlows->whereNull('status')->count() === 1;
    }
}
