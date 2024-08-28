<?php

namespace App\Models\ServiceRequests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'responsable_id','user_id','subdirection_ou_id', 'responsability_center_ou_id',
        'type', 'request_date', 'start_date', 'end_date', 'contract_type','contractual_condition',
        'service_description', 'programm_name','grade', 'other', 'normal_hour_payment', 'amount',
        'program_contract_type', 'weekly_hours', 'daily_hours', 'nightly_hours', 'estate',
        'estate_other', 'working_day_type','schedule_detail', 'working_day_type_other', 'subdirection_id',
        'responsability_center_id','budget_cdp_number', 'budget_item', 'budget_amount',
        'budget_date', 'contract_number','month_of_payment','establishment_id','profession_id','objectives','resolve','subt31',
        'additional_benefits','bonus_indications',
        'digera_strategy','rrhh_team','gross_amount', 'net_amount','sirh_contract_registration',
        'resolution_number','resolution_date','bill_number','total_hours_paid','total_paid',
        'has_resolution_file','has_resolution_file_at','payment_date','verification_code','observation','creator_id',
        'address','phone_number','email','signature_page_break'
        // 'rut', 'name','bank_id','account_number','pay_method','nationality',
    ];

    public function MonthOfPayment() {
      if ($this->payment_date) {
        if ($this->payment_date->format('m') == 1) {
          return "Enero";
        }elseif ($this->payment_date->format('m') == 2) {
          return "Febrero";
        }elseif ($this->payment_date->format('m') == 3) {
          return "Marzo";
        }elseif ($this->payment_date->format('m') == 4) {
          return "Abril";
        }elseif ($this->payment_date->format('m') == 5) {
          return "Mayo";
        }elseif ($this->payment_date->format('m') == 6) {
          return "Junio";
        }elseif ($this->payment_date->format('m') == 7) {
          return "Julio";
        }elseif ($this->payment_date->format('m') == 8) {
          return "Agosto";
        }elseif ($this->payment_date->format('m') == 9) {
          return "Septiembre";
        }elseif ($this->payment_date->format('m') == 10) {
          return "Octubre";
        }elseif ($this->payment_date->format('m') == 11) {
          return "Noviembre";
        }elseif ($this->payment_date->format('m') == 12) {
          return "Diciembre";
        }
      }
    }

    public function programm_name_id(){
      dd($this->programm_name);
      if (str_contains($this->programm_name,'No médico')) {
        dd("no médico");
      }
    }

    public function working_day_type_description(){
      if ($this->working_day_type == "DIURNO") {
        return "un largo de 08:00 a 20:00 hrs., una noche de 20:00 a 08:00 hrs. y dos días libres";
      }
      if ($this->working_day_type == "TERCER TURNO") {
        return "dos largos de 08:00 a 20:00 hrs., dos una noches de 20:00 a 08:00 hrs. y dos días libres";
      }
      if ($this->working_day_type == "CUARTO TURNO") {
        return "horario diruno";
      }
    }

    public function profession(){
      return $this->belongsTo('App\Models\Parameters\Profession','profession_id');
    }

    public function responsable(){
      return $this->belongsTo('App\Models\User','responsable_id')->withTrashed();
    }

    public function employee(){
      return $this->belongsTo('App\Models\User','user_id')->withTrashed();
    }

    public function creator(){
      return $this->belongsTo('App\Models\User','creator_id')->withTrashed();
    }

    public function SignatureFlows() {
    		return $this->hasMany('\App\Models\ServiceRequests\SignatureFlow');
    }

    public function establishment() {
    		return $this->belongsTo('\App\Models\Establishment');
    }

    // public function responsabilityCenter() {
    // 		return $this->belongsTo('\App\Models\ServiceRequests\ResponsabilityCenter');
    // }

    public function subdirection() {
    		return $this->belongsTo('\App\Models\Rrhh\OrganizationalUnit','subdirection_ou_id');
    }

    public function responsabilityCenter() {
        return $this->belongsTo('\App\Models\Rrhh\OrganizationalUnit','responsability_center_ou_id')->withTrashed();
    }

    public function shiftControls() {
    		return $this->hasMany('\App\Models\ServiceRequests\ShiftControl');
    }

    public function fulfillments() {
    		return $this->hasMany('\App\Models\ServiceRequests\Fulfillment');
    }

    public function bank(){
        return $this->belongsTo('\App\Models\Parameters\Bank', 'bank_id');
    }

    public static function getPendingRequests()
    {
        $count = 0;
        $user_id = auth()->id();
        $serviceRequests = ServiceRequest::whereHas("SignatureFlows", function($subQuery) use($user_id){
                                        $subQuery->whereNull('status')
                                                ->where( function($query) use($user_id){
                                                    $query->where('responsable_id', $user_id);
                                                }); 
                                    })
                                    ->wheredoesnthave("SignatureFlows", function($subQuery) {
                                        $subQuery->where('status',0);
                                    })
                                    ->with('SignatureFlows')
                                    ->get();

        foreach ($serviceRequests as $key => $serviceRequest) {
            //not rejected
            if ($serviceRequest->SignatureFlows->where('status', '===', 0)->count() == 0) {
                foreach ($serviceRequest->SignatureFlows->sortBy('sign_position') as $key2 => $signatureFlow) {
                    //with responsable_id
                    if ($user_id == $signatureFlow->responsable_id) {
                        if ($signatureFlow->status == NULL) {
                            // se encuentra signature flow anterior
                            $last_signature_flow = $serviceRequest->SignatureFlows->where('status', '!=', 2)->where('sign_position', $signatureFlow->sign_position - 1)->first();
                            if ($last_signature_flow) {
                                if ($last_signature_flow->status == NULL) {
                                
                                } 
                                else 
                                {
                                    $count += 1;
                                }
                            }
                        } 
                    }
                }
            }
        }

      return $count;
    }

    public function status(){
        if($this->SignatureFlows->where('status','===',0)->count() > 0){
            return "Rechazada";
        }elseif($this->SignatureFlows->whereNull('status')->count() > 0){
            return "Pendiente";
        }else{
            return "Finalizada";
        }
    }

    public function signedBudgetAvailabilityCert()
    {
        return $this->belongsTo('App\Models\Documents\SignaturesFile', 'signed_budget_availability_cert_id');
    }

    protected $table = 'doc_service_requests';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['request_date', 'start_date', 'end_date', 'budget_date', 'payment_date', 'resolution_date', 'has_resolution_file_at'];
}
