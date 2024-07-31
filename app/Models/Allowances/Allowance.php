<?php

namespace App\Models\Allowances;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

use App\Rrhh\Authority;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Documents\Signature;
use App\Models\User;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Documents\Approval;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Archive\Archive;

use App\Models\Documents\Correlative;

class Allowance extends Model implements Auditable
{
    use HasFactory;
    use softDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'correlative', 'folio_sirh', 'status', 'user_allowance_id', 'allowance_value_id', 'grade', 'law', 'contractual_condition_id', 
        'position', 'establishment_id', 'organizational_unit_allowance_id', 'place', 'reason',
        'overnight', 'accommodation', 'food','passage', 'means_of_transport', 'origin_commune_id', 'destination_commune_id', 
        'round_trip', 'from', 'to', 'total_days', 'total_half_days', 'fifty_percent_total_days', 'sixty_percent_total_days',
        'half_days_only', 'day_value', 'half_day_value', 'fifty_percent_day_value', 'sixty_percent_day_value', 'total_value', 
        'creator_user_id', 'creator_ou_id', 'document_date', 'signatures_file_id'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function userAllowance() {
        return $this->belongsTo('App\Models\User', 'user_allowance_id')->withTrashed();
    }

    public function organizationalUnitAllowance() {
        return $this->belongsTo('App\Rrhh\OrganizationalUnit', 'organizational_unit_allowance_id');
    }

    public function userCreator() {
        return $this->belongsTo('App\Models\User', 'creator_user_id')->withTrashed();
    }

    public function organizationalUnitCreator() {
        return $this->belongsTo('App\Rrhh\OrganizationalUnit', 'creator_ou_id');
    }

    public function originCommune() {
        return $this->belongsTo('App\Models\ClCommune', 'origin_commune_id');
    }

    public function destinationCommune() {
        return $this->hasMany('App\Models\Allowances\Destination', 'allowance_id');
    }

    public function files() {
        return $this->hasMany('App\Models\Allowances\AllowanceFile', 'allowance_id');
    }

    public function allowanceValue() {
        return $this->belongsTo('App\Models\Parameters\AllowanceValue', 'allowance_value_id');
    }

    public function allowanceEstablishment() {
        return $this->belongsTo('App\Models\Establishment', 'establishment_id');
    }

    public function allowanceSigns() {
        return $this->hasMany('App\Models\Allowances\AllowanceSign', 'allowance_id');
    }

    public function allowanceSignature(){
        return $this->belongsTo(Signature::class, 'signature_id');
    }

    public function contractualCondition(){
        return $this->belongsTo('App\Models\Parameters\ContractualCondition', 'contractual_condition_id');
    }

    public function destinations() {
        return $this->hasMany('App\Models\Allowances\Destination', 'allowance_id');
    }

    public function corrections() {
        return $this->hasMany('App\Models\Allowances\AllowanceCorrection', 'allowance_id');
    }

    /*
    public function getStatusValueAttribute() {
        switch($this->status) {
            case 'pending':
                return 'Pendiente';
                break;
            case 'complete':
                return 'Finalizado';
                break;
            case 'rejected':
                return 'Rechazado';
                break;
            case 'manual':
                return 'Carga Manual';
                break;
        }
    }
    */

    /**
     * Get all of the ModificationRequest's approvations.
     */
    public function approvals(): MorphMany{
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * Get one archive.
     */
    public function archive(): MorphOne{
        return $this->morphOne(Archive::class, 'archive');
    }

    public function getStatusValueAttribute(){
        switch ($this->status) {
            case 'pending':
                return 'Pendiente';
                break;
            case 'rejected':
                return 'Rechazado';
                break;
            case 'complete':
                return 'Finalizado';
                break;
            case 'manual':
                return 'Manual';
                break;
            case '':
                return '';
                break;
        }
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'warning';
                break;
            case 'rejected':
                return 'danger';
                break;
            case 'complete':
                return 'success';
                break;
            case 'manual':
                return 'primary';
                break;
            case '':
                return '';
                break;
        }
    }

    public function getLawValueAttribute(){
        switch ($this->law) {
            case '18834':
                return 'N° 18.834';
                break;
            case '19664':
                return 'N° 19.664';
                break;
            case '':
                return '';
                break;
        }
    }

    public function getMeansOfTransportValueAttribute(){
        switch ($this->means_of_transport) {
            case 'ambulance':
                return 'Ambulancia';
                break;
            case 'plane':
                return 'Avión';
                break;
            case 'bus':
                return 'Bus';
                break;
            case 'institutional vehicle':
                return 'Vehículo Institucional';
                break;
            case 'other':
                return 'Otro';
                break;
        }
    }

    public function getRoundTripValueAttribute(){
        switch ($this->round_trip) {
            case 'round trip':
              return 'Ida, vuelta';
              break;
            case 'one-way only':
              return 'Ida';
              break;
            case '':
              return '';
              break;
        }
    }

    public function getOvernightValueAttribute(){
        switch ($this->overnight) {
            case 1:
              return 'Sí';
              break;
            case 0:
              return 'No';
              break;
        }
    }

    public function getPassageValueAttribute(){
        switch ($this->passage) {
            case 1:
              return 'Sí';
              break;
            case 0:
              return 'No';
              break;
        }
    }

    public function getHalfDaysOnlyValueAttribute(){
        switch ($this->half_days_only) {
            case '1':
                return 'Sí';
                break;
            case '0':
                return 'No';
                break;
            case '':
                return 'No';
                break;
        }
    }

    public function getAccommodationValueAttribute(){
        switch ($this->accommodation) {
            case 1:
              return 'Sí';
              break;
            case 0:
              return 'No';
              break;
        }
    }

    public function getFoodValueAttribute(){
        switch ($this->food) {
            case 1:
              return 'Sí';
              break;
            case 0:
              return 'No';
              break;
        }
    }

    public function scopeSearch($query, $status_search, $search_id, $user_allowance_search, $status_sirh_search, $establishment_search){
        if ($status_search OR $search_id OR $user_allowance_search OR $status_sirh_search OR $establishment_search) {
            if($status_search != '' &&  ($status_search == 'pending' || $status_search == 'rejected')){
                $query->whereHas('allowanceSignature' ,function($query) use($status_search){
                    $query->where('status', $status_search);
                })
                ->orWhere('status', $status_search);
            }

            if($status_search != '' &&  $status_search == 'completed'){
                $query->whereHas('allowanceSignature' ,function($query) use($status_search){
                    $query->where('status', $status_search);
                });
            }

            if($search_id != ''){
                $query->where(function($q) use($search_id){
                    $q->where('correlative', $search_id);
                });
            }

            $array_user_allowance_search = explode(' ', $user_allowance_search);
            foreach($array_user_allowance_search as $word){
                $query->whereHas('userAllowance' ,function($query) use($word){
                    $query->where('name', 'LIKE', '%'.$word.'%')
                        ->orwhere('fathers_family','LIKE', '%'.$word.'%')
                        ->orwhere('mothers_family','LIKE', '%'.$word.'%');
                });
            }

            if($status_sirh_search != ''){
                $query->whereHas('allowanceSigns' ,function($query) use($status_sirh_search){
                    $query->where('status', $status_sirh_search);
                });
            }

            if($establishment_search != ''){
                $query->whereHas('allowanceEstablishment' ,function($query) use($establishment_search){
                    $query->where('establishment_id', $establishment_search);
                });
            }
        }
    }

    public function getFromFormatAttribute(){
        return Carbon::parse($this->from)->format('d-m-Y');
    }

    public function getToFormatAttribute(){
        return Carbon::parse($this->to)->format('d-m-Y');
    }

    /**
     * Simular un approval model.
     */
    public function getApprovalLegacyAttribute()
    {
        $approval = new Approval();

        $approval->status = true;
        $approval->approver_id = $this->allowanceSigns->first()->user_id;
        $approval->approver_at = $this->allowanceSigns->first()->date_sign;
        $approval->sent_to_ou_id = $this->allowanceSigns->first()->organizational_unit_id;
        
        return $approval;
    }

    // protected $dates = [
    //     'from', 'to', 'document_date'
    // ];

    protected $casts = [
        'from'  => 'date:d-m-Y',
        'to'    => 'date:Y-m-d'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($allowance) {
            //TODO: PARAMETRIZAR TYPE_ID VIATICOS
            $allowance->correlative = Correlative::getCorrelativeFromType(20, $allowance->organizationalUnitAllowance->establishment_id);
        });
    }

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $table = 'alw_allowances';
}