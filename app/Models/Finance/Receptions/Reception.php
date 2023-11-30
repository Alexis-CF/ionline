<?php

namespace App\Models\Finance\Receptions;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\User;
use App\Rrhh\OrganizationalUnit;
use App\Models\Finance\Receptions\ReceptionType;
use App\Models\Finance\Receptions\ReceptionItem;
use App\Models\Finance\PurchaseOrder;
use App\Models\File;
use App\Models\Establishment;
use App\Models\Documents\Numeration;
use App\Models\Documents\Approval;

class Reception extends Model
{
    use HasFactory;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'fin_receptions';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'number',
        'internal_number',
        'date',
        'reception_type_id',
        'purchase_order',
        'header_notes',
        'footer_notes',
        'partial_reception',
        'doc_type',
        'doc_number',
        'doc_date',
        'total',
        'establishment_id',
        'file',
        'status',
        'responsable_id',
        'responsable_ou_id',
        'creator_id',
        'creator_ou_id',
    ];
    
    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
        'date'      => 'date:Y-m-d',
        'doc_date'  => 'date:Y-m-d',
        'partial_reception' => 'boolean',
        'status'    => 'boolean',
    ];

    public function receptionType(): BelongsTo
    {
        return $this->belongsTo(ReceptionType::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order', 'code');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responsableOu(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creatorOu(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReceptionItem::class);
    }
    
    /**
     * Get all of the approvations of a model.
     */
    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * Get the numeration of the model.
     */
    public function numeration(): MorphOne
    {
        return $this->morphOne(Numeration::class, 'numerable');
    }

    /**
     * Get all of the files of a model.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
