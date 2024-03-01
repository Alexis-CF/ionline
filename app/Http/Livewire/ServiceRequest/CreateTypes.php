<?php

namespace App\Http\Livewire\ServiceRequest;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Rrhh\Authority;
use App\Rrhh\OrganizationalUnit;
use App\User;

class CreateTypes extends Component
{
  public $program_contract_type;
  public $type;
  public $subdirection_ou_id;

  public $subdirections;
  public $responsabilityCenters;
  public $users;
  public $a;
  public $signatures;

  public $signatureFlows = [];

  public function mount()
  {    
    // $this->users = User::whereHas('organizationalUnit', function ($q) {
    //   $q->where('establishment_id', auth()->user()->organizationalUnit->establishment->id);
    // })->get();
  }

  public function render()
  {
    //hospital
    if (auth()->user()->organizationalUnit->establishment_id == 38) {
        if (Authority::getAuthorityFromDate(1, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(1)->name);
        }
        if (Authority::getAuthorityFromDate(2, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(2)->name);
        }
        if (Authority::getAuthorityFromDate(40, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(40)->name);
        }
        if (Authority::getAuthorityFromDate(44, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(44)->name);
        }
        if (Authority::getAuthorityFromDate(59, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(59)->name);
        }
    }
    //servicio de salud
    else {
        if (Authority::getAuthorityFromDate(111, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(111)->name);
        }
        if (Authority::getAuthorityFromDate(88, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(88)->name);
        }
        if (Authority::getAuthorityFromDate(86, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(86)->name);
        }
        if (Authority::getAuthorityFromDate(85, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(85)->name);
        }
        if (Authority::getAuthorityFromDate(84, now(), ['manager']) == null) {
            dd("falta ingresar autoridad de " . OrganizationalUnit::find(84)->name);
        }
    }


    $this->signatureFlows = [];
    if ($this->type == NULL || $this->type == "Covid") {
      if ($this->program_contract_type == "Mensual") {
        $this->a = "mensual";
        if (auth()->user()->organizationalUnit->establishment_id == 38) {
          //Hector Reyno (CGU)
          if (auth()->user()->organizationalUnit->id == 24) {
            $this->signatureFlows['RRHH CGU'] = 10739552; //RR.HH del CGU
            $this->signatureFlows['Directora CGU'] = Authority::getAuthorityFromDate(24, now(), ['manager'])->user->id; // 24 - Consultorio General Urbano Dr. Hector Reyno
            $this->signatureFlows['S.D.G.A SSI'] = Authority::getAuthorityFromDate(2, now(), ['manager'])->user->id; // 2 - Subdirección de Gestion Asistencial / Subdirección Médica
            $this->signatureFlows['Planificación CG RRHH'] = Authority::getAuthorityFromDate(59, now(), ['manager'])->user->id; // 59 - Planificación y Control de Gestión de Recursos Humanos
            $this->signatureFlows['S.G.D.P SSI'] = Authority::getAuthorityFromDate(44, now(), ['manager'])->user->id; // 44 - Subdirección de Gestión y Desarrollo de las Personas
            $this->signatureFlows['S.D.A SSI'] = Authority::getAuthorityFromDate(40, now(), ['manager'])->user->id; // 31 - Subdirección de Recursos Físicos y Financieros
            //SE COMENTA Y QUITA DEL FLUJO A PETICIÓN DE LA SUBDIRECTORA
            // $this->signatureFlows[Authority::getAuthorityFromDate(1,now(),['manager'])->position . " - " . Authority::getAuthorityFromDate(1,now(),['manager'])->user->organizationalUnit->establishment->name] = Authority::getAuthorityFromDate(1,now(),['manager'])->user->id; // 1 - Dirección
          }

          //servicio de salud iqq
          else {
            $this->signatureFlows['S.D.G.A SSI'] = Authority::getAuthorityFromDate(2, now(), ['manager'])->user->id; // 2 - Subdirección de Gestion Asistencial / Subdirección Médica
            $this->signatureFlows['Planificación CG RRHH'] = Authority::getAuthorityFromDate(59, now(), ['manager'])->user->id; // 59 - Planificación y Control de Gestión de Recursos Humanos
            $this->signatureFlows['S.G.D.P SSI'] = Authority::getAuthorityFromDate(44, now(), ['manager'])->user->id; // 44 - Subdirección de Gestión y Desarrollo de las Personas
            $this->signatureFlows['S.D.A SSI'] = Authority::getAuthorityFromDate(40, now(), ['manager'])->user->id; // 31 - Subdirección de Recursos Físicos y Financieros
          }
        }
        //hospital
        // se comenta 2 y 44, ya que se solicitó quitar autoridades del ssi de flujo de hospital
        elseif (auth()->user()->organizationalUnit->establishment_id == 1) {
          $this->signatureFlows['Subdirector'] = Authority::getAuthorityFromDate(88, now(), ['manager'])->user->id; // 88 - Subdirección Médica - 9882506 - (iriondo)
          $this->signatureFlows['S.G.D.P Hospital'] = Authority::getAuthorityFromDate(86, now(), ['manager'])->user->id; // 86 - Subdirección de Gestión de Desarrollo de las Personas
          $this->signatureFlows['Jefe Finanzas'] = Authority::getAuthorityFromDate(111, now(), ['manager'])->user->id; // 11 - Departamento de Finanzas
          if ($this->subdirection_ou_id == 85) {$this->signatureFlows['S.D.G.C Hospital'] = Authority::getAuthorityFromDate(85, now(), ['manager'])->user->id;}
          $this->signatureFlows[Authority::getAuthorityFromDate(84, now(), ['manager'])->position . " - " . Authority::getAuthorityFromDate(84, now(), ['manager'])->user->organizationalUnit->establishment->name] = Authority::getAuthorityFromDate(84, now(), ['manager'])->user->id; // 84 - Dirección
        }
      } elseif ($this->program_contract_type == "Horas") {
        $this->a = "horas";
        if (auth()->user()->organizationalUnit->establishment_id == 38) {
          //Hector Reyno (CGU)
          if (auth()->user()->organizationalUnit->id == 24) {
            $this->signatureFlows['Funcionario'] = 10739552; // 24 - Consultorio General Urbano Dr. Hector Reyno
            $this->signatureFlows['Directora CGU'] = Authority::getAuthorityFromDate(24, now(), ['manager'])->user->id; // 24 - Consultorio General Urbano Dr. Hector Reyno
            $this->signatureFlows['S.G.D.P SSI'] = Authority::getAuthorityFromDate(44, now(), ['manager'])->user->id; // 44 - Subdirección de Gestión y Desarrollo de las Personas
            $this->signatureFlows['S.D.A SSI'] = Authority::getAuthorityFromDate(40, now(), ['manager'])->user->id; // 31 - Subdirección de Recursos Físicos y Financieros
          }
          //servicio de salud iqq
          else {
            $this->signatureFlows['Planificación CG RRHH'] = Authority::getAuthorityFromDate(59, now(), ['manager'])->user->id; // 59 - Planificación y Control de Gestión de Recursos Humanos
            $this->signatureFlows['S.G.D.P SSI'] = Authority::getAuthorityFromDate(44, now(), ['manager'])->user->id; // 44 - Subdirección de Gestión y Desarrollo de las Personas
            $this->signatureFlows['S.D.A SSI'] = Authority::getAuthorityFromDate(40, now(), ['manager'])->user->id; // 31 - Subdirección de Recursos Físicos y Financieros
          }
        }
        //hospital
        elseif (auth()->user()->organizationalUnit->establishment_id == 1) {
          $this->signatureFlows['Subdirector'] = Authority::getAuthorityFromDate(88, now(), ['manager'])->user->id; // 88 - Subdirección Médica - 9882506 - (iriondo)
          $this->signatureFlows['S.G.D.P Hospital'] = Authority::getAuthorityFromDate(86, now(), ['manager'])->user->id; // 86 - Subdirección de Gestión de Desarrollo de las Personas
          $this->signatureFlows['Jefe Finanzas'] = Authority::getAuthorityFromDate(111, now(), ['manager'])->user->id; // 11 - Departamento de Finanzas
          if ($this->subdirection_ou_id == 85) {$this->signatureFlows['S.D.G.C Hospital'] = Authority::getAuthorityFromDate(85, now(), ['manager'])->user->id;}
        }
      }
    } else {
      if (auth()->user()->organizationalUnit->establishment_id == 1) {
        $this->a = "suma";
        $this->signatureFlows['S.G.D.P Hospital'] = Authority::getAuthorityFromDate(86, now(), ['manager'])->user->id;
        $this->signatureFlows['Jefe Finanzas'] = Authority::getAuthorityFromDate(111, now(), ['manager'])->user->id;
        if ($this->subdirection_ou_id == 85) {$this->signatureFlows['S.D.G.C Hospital'] = Authority::getAuthorityFromDate(85, now(), ['manager'])->user->id;}
        $this->signatureFlows[Authority::getAuthorityFromDate(84, now(), ['manager'])->position . " - " . Authority::getAuthorityFromDate(84, now(), ['manager'])->user->organizationalUnit->establishment->name] = Authority::getAuthorityFromDate(84, now(), ['manager'])->user->id;
      } else {
        $this->signatureFlows['Planificación CG RRHH'] = Authority::getAuthorityFromDate(59, now(), ['manager'])->user->id; // 59 - Planificación y Control de Gestión de Recursos Humanos
        $this->signatureFlows['S.G.D.P SSI'] = Authority::getAuthorityFromDate(44, now(), ['manager'])->user->id; // 44 - Subdirección de Gestión y Desarrollo de las Personas
        $this->signatureFlows['S.D.A SSI'] = Authority::getAuthorityFromDate(40, now(), ['manager'])->user->id; // 31 - Subdirección de Recursos Físicos y Financieros
      }
    }

    /*
    [Planificación CG RRHH] => 15685508 
    [S.G.D.P SSI] => 15685508 
    [S.D.A SSI] => 17432199 
    */
    $this->signatures = [];
    foreach($this->signatureFlows as $ou_name => $user_id)
    {
      $this->signatures[$ou_name] = User::find($user_id);
    }

    // $this->emit('listener',$this->program_contract_type, $this->type);
    return view('livewire.service-request.create-types');
  }
}
