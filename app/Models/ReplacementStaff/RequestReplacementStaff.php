<?php

namespace App\Models\ReplacementStaff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Rrhh\Authority;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Documents\Approval;

use App\Models\Establishment;


class RequestReplacementStaff extends Model implements Auditable
{
    use HasFactory;
    use softDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'form_type', 'name', 'profile_manage_id', 'law', 'degree', 'start_date', 'end_date',
        'legal_quality_manage_id', 'salary', 'fundament_manage_id', 'fundament_detail_manage_id',
        'name_to_replace', 'run', 'dv', 'other_fundament', 'work_day', 'other_work_day',
        'charges_number','job_profile_file', 'request_verification_file',
        'ou_of_performance_id', 'replacement_staff_id', 'user_id'
    ];

    public function requestFather() {
        return $this->belongsTo('App\Models\ReplacementStaff\RequestReplacementStaff', 'request_id');
    }
    public function requestChilds() {
        return $this->hasMany('App\Models\ReplacementStaff\RequestReplacementStaff', 'request_id');
    }

    public function profile_manage() {
        return $this->belongsTo('App\Models\ReplacementStaff\ProfileManage');
    }

    public function legalQualityManage() {
        return $this->belongsTo('App\Models\ReplacementStaff\LegalQualityManage');
    }

    public function fundamentManage() {
        return $this->belongsTo('App\Models\ReplacementStaff\RstFundamentManage')->withTrashed();
    }

    public function fundamentDetailManage() {
        return $this->belongsTo('App\Models\ReplacementStaff\FundamentDetailManage');
    }
    
    //user_id ORIGINALMENTE QUIEN REGISTRA SOLICITUD
    public function user() {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function organizationalUnit() {
        return $this->belongsTo('App\Models\Rrhh\OrganizationalUnit')->withTrashed();
    }

    public function establishment() {
        return $this->belongsTo(Establishment::class);
    }

    public function requesterUser() {
        return $this->belongsTo('App\Models\User', 'requester_id')->withTrashed();
    }

    public function ouPerformance() {
        return $this->belongsTo('App\Models\Rrhh\OrganizationalUnit', 'ou_of_performance_id')->withTrashed();
    }

    public function replacementStaff() {
        return $this->belongsTo('App\Models\ReplacementStaff\ReplacementStaff');
    }

    public function requestSign() {
        return $this->hasMany('App\Models\ReplacementStaff\RequestSign');
    }

    public function technicalEvaluation() {
        return $this->hasOne('App\Models\ReplacementStaff\TechnicalEvaluation');
    }

    public function assignEvaluations() {
        return $this->hasMany('App\Models\ReplacementStaff\AssignEvaluation');
    }

    public function positions(){
        return $this->hasMany('App\Models\ReplacementStaff\Position');
    }

    public function budgetItem() {
        return $this->belongsTo('App\Models\Parameters\BudgetItem');
    }

    public function signaturesFile(){
        return $this->belongsTo('App\Models\Documents\SignaturesFile');
    }

    /**
     * Get all of the ModificationRequest's approvations.
     */
    public function approvals(): MorphMany{
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function getLegalQualityValueAttribute() {
        switch($this->legal_quality) {
          case 'to hire':
            return 'Contrata';
            break;
          case 'fee':
            return 'Honorarios';
            break;
        }
    }

    public function getWorkDayValueAttribute() {
        switch($this->work_day) {
          case 'diurnal':
            return 'Diurno';
            break;
          case 'third shift':
            return 'Tercer Turno';
            break;
          case 'fourth shift':
            return 'Cuarto Turno';
            break;
          case 'other':
            return 'Otro';
            break;
        }
    }

    public function getFundamentValueAttribute() {
        switch($this->fundament) {
          case 'replacement':
            return 'Reemplazo o Suplencia';
            break;
          case 'quit':
            return 'Renuncia';
            break;
          case 'allowance without payment':
            return 'Permiso sin goce de sueldo';
            break;
          case 'regularization work position':
            return 'Regulación de cargos';
            break;
          case 'expand work position':
            return 'Cargo expansión';
            break;
          case 'vacations':
            return 'Feriado legal';
            break;
          case 'other':
            return 'Otro';
            break;
        }
    }

    public function getStatusValueAttribute() {
        switch($this->request_status) {
            case 'pending':
                return 'Pendiente';
                break;
            case 'complete':
                return 'Finalizada';
                break;
            case 'rejected':
                return 'Rechazada';
                break;
            case 'to assign':
                return 'Pendiente';
                break;
            case 'finance sign':
                return 'Pendiente';
                break;
        }
    }

    public function getFormTypeValueAttribute() {
        switch($this->form_type) {
          case 'replacement':
            return 'Reemplazo';
            break;
          case 'announcement':
            return 'Convocatoria';
            break;
        }
    }

    public function getNumberOfDays() {
        $numberDays = 1 + $this->end_date->diff($this->start_date)->format("%a");
        return $numberDays;
    }

    public static function getCurrentContinuity($requestReplacementStaff) {
        if($requestReplacementStaff->requestChilds->count() > 0){
            if($requestReplacementStaff->requestChilds->last()->request_status == 'complete' &&
                $requestReplacementStaff->requestChilds->last()->end_date < now()->toDateString()){
                return  $currentContinuity = 'no current';
            }
            if($requestReplacementStaff->requestChilds->last()->request_status == 'rejected'){
                return  $currentContinuity = 'no current';
            }
        }
        else{
            return  $currentContinuity = 'no childs';
        }
    }

    public function scopeSearch($query, $form_type_search, $status_search, $id_search, $start_date_search, 
        $end_date_search, $name_search, $fundament_search, $fundament_detail_search, $name_to_replace_search,
        $sub_search)
    {
        if ($form_type_search OR $status_search OR $id_search OR $start_date_search OR $end_date_search OR $name_search OR 
            $fundament_search OR $fundament_detail_search OR $name_to_replace_search OR $sub_search) {
            if($form_type_search != ''){
                $query->where(function($q) use($form_type_search){
                    $q->where('form_type', $form_type_search);
                });
            }
            if($status_search != ''){
                $query->where(function($q) use($status_search){
                    $q->where('request_status', $status_search);
                });
            }
            if($id_search != ''){
                $query->where(function($q) use($id_search){
                    $q->where('id', 'LIKE', '%'.$id_search.'%');
                });
            }
            if($start_date_search != '' && $end_date_search != ''){
                $query->where(function($q) use($start_date_search, $end_date_search){
                    $q->whereBetween('created_at', [$start_date_search, $end_date_search." 23:59:59"])->get();
                });
            }
            if($name_search != ''){
                $query->where(function($q) use($name_search){
                    $q->where('name', 'LIKE', '%'.$name_search.'%');
                });
            }
            if($fundament_search != 0){
                $query->whereHas('fundamentManage', function($q) use ($fundament_search){
                    $q->Where('fundament_manage_id', $fundament_search);
                });
            }
            if($fundament_detail_search != 0){
                $query->whereHas('fundamentDetailManage', function($q) use ($fundament_detail_search){
                    $q->Where('fundament_detail_manage_id', $fundament_detail_search);
                });
            }
            if($name_to_replace_search != ''){
                $query->where(function($q) use($name_to_replace_search){
                    $q->where('name_to_replace', 'LIKE', '%'.$name_to_replace_search.'%')
                    ->orwhere('run','LIKE', '%'.$name_to_replace_search.'%');
                });
            }
            if(!empty($sub_search)){
                $query->where(function($q) use($sub_search){
                    $q->whereIn('organizational_unit_id', $sub_search);
                });
            }
        }
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $dates = [
        'start_date','end_date'
    ];

    protected $table = 'rst_request_replacement_staff';
}
