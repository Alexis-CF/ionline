<?php

namespace App\Livewire\ProfAgenda\Reports;

use Livewire\Component;

use Carbon\Carbon;
use App\Models\WebService\Fonasa;

use App\Models\ProfAgenda\OpenHour;

class SirsapReport extends Component
{
    public $finicio;
    public $ftermino;
    public $data;

    // public function mount(){
    //     $this->finicio = Carbon::createFromDate('2023-11-01');
    //     $this->ftermino = Carbon::createFromDate('2023-11-15');
    // }

    public function search(){
        $establishment_id = auth()->user()->establishment_id;

        $openHours = OpenHour::whereBetween('start_date', [$this->finicio, $this->ftermino])
                            ->where('blocked', '0')
                            ->whereHas('profesional', function ($query) use ($establishment_id) {
                                $query->where('establishment_id', $establishment_id);
                            })
                            ->with('patient')
                            ->when(!auth()->user()->hasRole('Agenda Salud del Trabajdor: Administrador'), function ($q) {
                                return $q->where('profesional_id', auth()->user()->id);
                            })
                            ->get();

        $this->data = null;
        foreach($openHours as $openHour){
            if($openHour->patient){
                $this->data[$openHour->profession->name][$openHour->activityType->name]['Agendadas'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)] = 0; 
                // $this->data[$openHour->profession->name][$openHour->activityType->name]['Agendadas'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)] = 0; 
                $this->data[$openHour->profession->name][$openHour->activityType->name]['Asiste'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)] = 0; 
                // $this->data[$openHour->profession->name][$openHour->activityType->name]['Asiste'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)] = 0;
                if($openHour->assistance == 1){
                    $this->data[$openHour->profession->name][$openHour->activityType->name]['Asiste_cantidad'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)][$openHour->patient->id] = 0;
                }
                $this->data[$openHour->profession->name][$openHour->activityType->name]['No_asiste'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)] = 0; 
                // $this->data[$openHour->profession->name][$openHour->activityType->name]['No_asiste'][($openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender)] = 0; 
            }
            $this->data[$openHour->profession->name][$openHour->activityType->name]['No_agendadas'] = 0; 
            $this->data[$openHour->profession->name][$openHour->activityType->name]['Total'] = 0; 
        }
        foreach($openHours as $openHour){
            if($openHour->patient){
                $this->data[$openHour->profession->name][$openHour->activityType->name]['Agendadas'][$openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender] += 1; 
                if($openHour->assistance == 1){
                    $this->data[$openHour->profession->name][$openHour->activityType->name]['Asiste'][$openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender] += 1; 
                    $this->data[$openHour->profession->name][$openHour->activityType->name]['Asiste_cantidad'][$openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender][$openHour->patient->id] += 1;
                }
                elseif($openHour->assistance == 0){
                    $this->data[$openHour->profession->name][$openHour->activityType->name]['No_asiste'][$openHour->patient->gender == null ? 'Otro' : $openHour->patient->gender] += 1; 
                }
                $this->data[$openHour->profession->name][$openHour->activityType->name]['Total'] += 1;
            }else{
                $this->data[$openHour->profession->name][$openHour->activityType->name]['No_agendadas'] += 1; 
                $this->data[$openHour->profession->name][$openHour->activityType->name]['Total'] += 1;
            }  
        }
        // dd($this->data);
        // dd($data);
    }

    public function render()
    {
        return view('livewire.prof-agenda.reports.sirsap-report');
    }
}
