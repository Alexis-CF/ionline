<?php

namespace App\Http\Livewire\ReplacementStaff;

use Livewire\Component;
use App\Rrhh\OrganizationalUnit;
use Illuminate\Support\Facades\Auth;
use App\Models\ReplacementStaff\StaffManage;

class OuStaffSelect extends Component
{
    public $selectedOu = null;
    public $selectedReplacementStaff = null;

    public $staffManageByOu = null;

    public $requestReplacementStaff;

    /* Para editar y precargar los select */
    public $ouSelected = null;
    public $replacementStaffSelected = null;

    public function mount(){
        if($this->requestReplacementStaff) {
            $this->selectedOu = $this->requestReplacementStaff->ou_of_performance_id;

            $this->staffManageByOu = StaffManage::where('organizational_unit_id', $this->selectedOu)->get();

            $this->selectedReplacementStaff = $this->requestReplacementStaff->replacement_staff_id;
        }

        // $this->selectedOu = $request->ou_of_performance_id;
        // $this->selectedReplacementStaff = $request->replacement_staff_id;

        // if($request->type != null){
        //   $this->establishments = Establecimiento::year($this->selectedYear)
        //     ->where('tablero_poblacion', 1)
        //     ->orderBy('comuna')
        //     ->get();
        // }

    }

    public function render()
    {
        return view('livewire.replacement-staff.ou-staff-select', [
            'organizationalUnits' => OrganizationalUnit::where('id', Auth::user()->organizational_unit_id)
              ->get()
        ]);
    }

    public function updatedselectedOu($ou_id){
        $this->staffManageByOu = StaffManage::where('organizational_unit_id', $ou_id)
            ->get();
    }
}
