<?php

namespace App\Http\Livewire\PurchasePlan;

use App\Models\Parameters\Parameter;
use Livewire\Component;
use App\Models\PurchasePlan\PurchasePlan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Rrhh\OrganizationalUnit;

class SearchPurchasePlan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $selectedId, $selectedStatus, $selectedSubject, $selectedStartDate, $selectedEndDate, $selectedUserCreator, $selectedUserResponsible,
        $selectedResponsibleOuName, $selectedProgram;

    public $index;

    protected $listeners = ['searchedResponsibleOu', 'clearResponsibleOu'];

    protected $queryString = ['selectedId', 'selectedStatus', 'selectedSubject', 'selectedStartDate', 'selectedEndDate', 'selectedUserCreator',
        'selectedUserResponsible', 'selectedResponsibleOuName', 'selectedProgram'];

    public function delete(PurchasePlan $purchasePlan)
    {
        $purchasePlan->delete();
    }

    public function render()
    {
        $query = PurchasePlan::query()->with('organizationalUnit.establishment', 'approvals.sentToOu', 'userResponsible', 'userCreator')->latest();

        if($this->index == 'own'){
            //PLANES DE COMPRAS: OWN INDEX
            $organizationalUnitIn = array();
            $organizationalUnitIn[] = auth()->user()->organizationalUnit->id;

            if(auth()->user()->organizationalUnit->id == Parameter::get('ou', 'ControlEquipos') || auth()->user()->organizationalUnit->id == Parameter::get('ou', 'ControlInfraestructura')){
                $organizationalUnitIn[] = Parameter::get('ou', 'DeptoRRFF');
            }

            if(auth()->user()->organizationalUnit->id == Parameter::get('ou', 'DeptoAPS')){
                $childs_array = auth()->user()->organizationalUnit->childs->pluck('id')->toArray();
                $organizationalUnitIn = [auth()->user()->organizationalUnit->id, ...$childs_array];
            }

            $purchasePlans = $query
                ->where('user_creator_id', auth()->id())
                ->orWhere('user_responsible_id', auth()->id())
                ->orWhereIn('organizational_unit_id', $organizationalUnitIn)
                ->when(auth()->user()->organizationalUnit->id == Parameter::get('ou', 'SaludMentalSSI'),
                    fn($q) => $q->orwhereHas('organizationalUnit', 
                        fn($q2) => $q2->whereIn('establishment_id', explode(',', Parameter::get('establishment', 'EstablecimientosDispositivos')))))
                ->search($this->selectedId,
                    $this->selectedStatus,
                    $this->selectedSubject,
                    $this->selectedStartDate,
                    $this->selectedEndDate,
                    $this->selectedUserCreator,
                    $this->selectedUserResponsible,
                    $this->selectedResponsibleOuName,
                    $this->selectedProgram)
                /*
                ->search($this->selectedStatus,
                    $this->selectedId,
                    $this->selectedUserAllowance)
                */
                ->paginate(30);
        }

        if($this->index == 'all'){
            //PLANES DE COMPRAS: ALL INDEX 
            $purchasePlans = $query
                /*
                ->where('user_allowance_id', auth()->id())
                ->orWhere('creator_user_id', auth()->id())
                ->orWhere('organizational_unit_allowance_id', auth()->user()->organizationalUnit->id)
                ->search($this->selectedStatus,
                    $this->selectedId,
                    $this->selectedUserAllowance)
                */
                ->search($this->selectedId,
                    $this->selectedStatus,
                    $this->selectedSubject,
                    $this->selectedStartDate,
                    $this->selectedEndDate,
                    $this->selectedUserCreator,
                    $this->selectedUserResponsible,
                    $this->selectedResponsibleOuName,
                    $this->selectedProgram)
                ->paginate(30);
        }

        if($this->index == 'pending'){
            //PLANES DE COMPRAS: PENDING INDEX
            /** Soy manager de alguna OU hoy? */
            $ous = auth()->user()->amIAuthorityFromOu->pluck('organizational_unit_id')->toArray();

            $purchasePlans = $query
                ->whereHas('approvals', fn($q) => $q->where('active', 1)->whereNull('status')->whereIn('sent_to_ou_id', $ous))
                ->search($this->selectedId,
                    $this->selectedStatus,
                    $this->selectedSubject,
                    $this->selectedStartDate,
                    $this->selectedEndDate,
                    $this->selectedUserCreator,
                    $this->selectedUserResponsible,
                    $this->selectedResponsibleOuName,
                    $this->selectedProgram)
                ->paginate(30);
        }

        if($this->index == 'report: ppl-items'){
            //PLANES DE COMPRAS: REPORT 
            $purchasePlans = PurchasePlan::with('organizationalUnit.establishment', 'userResponsible', 'userCreator', 'programName', 'purchasePlanItems.unspscProduct')->latest()
                /*
                ->where('user_allowance_id', auth()->id())
                ->orWhere('creator_user_id', auth()->id())
                ->orWhere('organizational_unit_allowance_id', auth()->user()->organizationalUnit->id)
                ->search($this->selectedStatus,
                    $this->selectedId,
                    $this->selectedUserAllowance)
                */
                ->paginate(150);
        }

        if($this->index == 'assign_purchaser'){
            $pendingPurchasePlans = 
                PurchasePlan::with('organizationalUnit.establishment', 'userResponsible', 'userCreator', 'programName', 'purchasePlanItems.unspscProduct')
                    ->latest()
                    ->where('status', 'approved')
                    ->whereNull('assign_user_id')
                    ->paginate(50);
            
            $assignedPurchasePlans = 
                PurchasePlan::with('organizationalUnit.establishment', 'userResponsible', 'userCreator', 'programName', 'purchasePlanItems.unspscProduct')
                    ->latest()
                    ->whereNotNull('assign_user_id')
                    ->paginate(150);        

            return view('livewire.purchase-plan.search-purchase-plan', compact('pendingPurchasePlans', 'assignedPurchasePlans'));
        }

        if($this->index == 'my_assigned_plans'){
            $purchasePlans = $query
                ->where('assign_user_id', auth()->id())
                ->paginate(150);
        }

        return view('livewire.purchase-plan.search-purchase-plan', compact('purchasePlans'));
    }

    public function searchedResponsibleOu(OrganizationalUnit $organizationalUnit){
        $this->selectedResponsibleOuName = $organizationalUnit->id;
    }

    public function clearResponsibleOu(){
        $this->selectedResponsibleOuName = null;
    }

    public function updatingSelectedId(){
        $this->resetPage();
    }

    public function updatingSelectedStatus(){
        $this->resetPage();
    }

    public function updatingSelectedSubject(){
        $this->resetPage();
    }

    public function updatingSelectedStartDate(){
        $this->resetPage();
    }

    public function updatingSelectedEndDate(){
        $this->resetPage();
    }

    public function updatingSelectedUserCreator(){
        $this->resetPage();
    }

    public function updatingSelectedUserResponsible(){
        $this->resetPage();
    }

    public function updatingSelectedResponsibleOuName(){
        $this->resetPage();
    }

    public function updatingSelectedProgram(){
        $this->resetPage();
    }
}
