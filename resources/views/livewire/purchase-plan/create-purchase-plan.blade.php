<div>
    <h6 class="small mb-4"><b>1. Descripción</b></h6>
    
    <div class="row g-3 mb-3">
        <fieldset class="form-group col-4">
            <label for="for_user_responsible_id">Funcionario Responsable</label>
            @livewire('search-select-user', [
                'selected_id'   => 'user_responsible_id',
                'required'      => 'required',
                'emit_name'     => 'searchedUser',
                'user'          => $purchasePlanToEdit->userResponsible ?? null,
                'disabled'      => $disabled
            ])
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_telephone">Teléfono</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="telephone" {{$disabled}}>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_email">Correo Electrónico</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="email" {{$disabled}}>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_position">Cargo / Función</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="position" {{$disabled}}>
        </fieldset>
    </div>

    <div class="row g-3 mb-3">
        <fieldset class="form-group col-6">
            <label for="for_user_allowance_id">Unidad Organizacional</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="organizationalUnit" {{ $readonly }} {{$disabled}} >
        </fieldset>

        <fieldset class="form-group col-6">
            <label for="for_program">Programa</label>
            @livewire('search-select-program',[
                    'emit_name' => 'searchedProgram',
                    'program'   => $purchasePlanToEdit->programName ?? null,
                    'disabled'  => $disabled
            ])
        </fieldset>
    </div>

    <div class="row g-3 mb-3">
        <fieldset class="form-group col-6">
            <label for="for_user_allowance_id">Asunto</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="subject" {{$disabled}}>
        </fieldset>
        <fieldset class="form-group col-3">
            <label for="for_period">Periodo</label>
            <select class="form-select" wire:model.defer="period" {{$disabled}}>
                <option value="0">Seleccione</option>
                <option value="2023" disabled>2023</option>
                <option value="2024">2024</option>
            </select>
        </fieldset>
    </div>

    <div class="row g-3">
        <div class="form-group col-6">
            <label for="for_description">Descripción general del proyecto o adquisición</label>
            <textarea class="form-control" rows="3" autocomplete="off" wire:model.defer="description" {{$disabled}}></textarea>
        </div>
        <div class="form-group col-6">
            <label for="for_purpose">Propósito general del proyecto o adquisición</label>
            <textarea class="form-control" rows="3" autocomplete="off" wire:model.defer="purpose" {{$disabled}}></textarea>
        </div>
    </div>
    
    <br>
    <hr>

    <h6 class="small"><b>2. Ítems a comprar</b></h6> <br>
    
    @livewire('request-form.item.request-form-items', [
            'savedItems'            => $purchasePlanToEdit->purchasePlanItems ?? null,
            'savedTypeOfCurrency'   => null,
            'bootstrap'             => 'v5',
            'form'                  => 'purchase_plan'       
    ])
    <br>

    @if(count($errors) > 0 && $validateMessage == "description")
        <div class="alert alert-danger">
            <p>Corrige los siguientes errores:</p>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <h6 class="small"><b>Historial de Rechazos</b></h6> <br>

    @if($purchasePlanToEdit->trashedApprovals)
        <div class="table-responsive">
            <table class="table table-bordered table-sm small">
                <thead>
                    <tr class="text-center">
                        <th width="8%" class="table-secondary">Fecha</th>
                        <th width="" class="table-secondary">Motivo Rechazo</th>
                        <th width="20%" class="table-secondary">Usuario</th>
                        <th width="20%" class="table-secondary">Unidad Organizacional</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasePlanToEdit->trashedApprovals as $approval)
                        <tr class="text-center">
                            <td>{{ $approval->approver_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $approval->approver_observation }}</td> 
                            <td>{{ $approval->approver->FullName }}</td>
                            <td>{{ $approval->sentToOu->name }}</td>         
                        </tr>
                    @endforeach
                <tbody>
            </table>
        </div>
    @endif
    
    {{--
    <div class="row g-3">
        <div class="col-12">
            <button wire:click="savePurchasePlan('save')" class="btn btn-primary float-end" type="button">
                @if(Route::is('purchase_plan.edit') && $purchasePlanToEdit->getStatus() == 'Rechazado')
                    <i class="fas fa-undo"></i> Restablecer Solicitud
                @else
                    <i class="fas fa-save"></i> Guardar
                @endif
            </button>
            <button wire:click="savePurchasePlan('sent')" class="btn btn-success float-end me-2" type="button" @if($purchasePlanToEdit && $purchasePlanToEdit->hasApprovals()) disabled @endif>
                <i class="fas fa-paper-plane"></i> Guardar y Enviar
            </button>
        </div>
    </div>
    --}}

    <div class="row g-3">
        <div class="col-12">
            <button wire:click="savePurchasePlan('save')" class="btn btn-primary float-end" type="button">
                <i class="fas fa-save"></i> Guardar
            </button>
            {{--<button wire:click="savePurchasePlan('sent')" class="btn btn-success float-end me-2" type="button" @if($purchasePlanToEdit && $purchasePlanToEdit->hasApprovals()) disabled @endif>
                <i class="fas fa-paper-plane"></i> Guardar y Enviar
            </button>--}}
        </div>
    </div>

    <br>
    <br>
</div>
