<?php

namespace App\Http\Livewire\ServiceRequest;

use Livewire\Component;
use App\Models\ServiceRequests\SignatureFlow;
use Illuminate\Support\Facades\Session;

use App\User;
use Illuminate\Support\Facades\Auth;

class ApprovalWorkflow extends Component
{

    public $serviceRequest;
    public $signatureFlowId;
    public $showDiv = false;
    public $status;
    public $observation;

    public $edit_signatureFlow;
    public $edit_status;
    public $edit_observation;
    public $edit_user;

    protected $listeners = ['userSelected'];

    protected $rules = [
        'status'  => 'required',
        'edit_status' => 'required',
        'edit_observation' => 'edit_observation'
    ];

    public function mount($serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function render()
    {
        return view('livewire.service-request.approval-workflow');
    } 

    public function edit(SignatureFlow $signatureFlow){
        $this->showDiv =! $this->showDiv;

        $this->edit_signatureFlow = $signatureFlow;
        $this->edit_status = $signatureFlow->status;
        $this->edit_observation = $signatureFlow->observation;
        $this->edit_user = $signatureFlow->user;
    }

    public function userSelected($user_id)
    {
        $this->edit_user = User::find($user_id);
    }

    public function save(){
        // dd($this->edit_user_id);
        $this->edit_signatureFlow->responsable_id = $this->edit_user->id;
        $this->edit_signatureFlow->status = $this->edit_status;
        $this->edit_signatureFlow->signature_date = now();
        $this->edit_signatureFlow->observation = $this->edit_observation;
        $this->edit_signatureFlow->save();

        Session::flash('approval-workflow', 'Se ha modificado el flujo de firmas.');

        $this->showDiv = false;
        $this->serviceRequest->refresh();
        $this->render();
    }

    public function delete(){
        $this->edit_signatureFlow->delete();
        Session::flash('approval-workflow', 'Se ha eliminado el flujo de firmas.');

        $this->showDiv = false;
        $this->serviceRequest->refresh();
        $this->render();
    }

    public function saveSignatureFlow($signatureFlowId)
    {
        $signatureFlow = SignatureFlow::find($signatureFlowId);

        if($signatureFlow->responsable_id != Auth::user()->id){
            Session::flash('approval-workflow', 'El usuario específicado para la visación no corresponde con el usuario logeado en el sistema.');
            return;
        }

        $signatureFlow->status = $this->status;
        $signatureFlow->observation = $this->observation;
        $signatureFlow->signature_date = now();
        $signatureFlow->save();
        Session::flash('approval-workflow', 'La firma se ha guardado correctamente.');

        $this->serviceRequest->refresh();
        $this->render();
    }
}
