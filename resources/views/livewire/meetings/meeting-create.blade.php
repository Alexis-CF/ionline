<div class="mt-3">
    <h6 class="mt-3"><b>1- Descripción reunión</b></h6>

    <div class="row g-3 mb-3">
        {{--
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_user_responsible_id">Nombre Funcionario responsable:</label>
            @livewire('search-select-user', [
                'selected_id'   => 'user_responsible_id',
                'required'      => 'required',
                'emit_name'     => 'searchedUser',
                'user'          => $meetingToEdit->userResponsible ?? null
            ])
        </fieldset>
        --}}

        <fieldset class="form-group col-12 col-sm-2">
            <label for="for_date">Fecha Reunión</label>
            <input type="date" class="form-control" wire:model.defer="date" id="for_date">
            @error('from') <span class="text-danger error small">{{ $message }}</span> @enderror
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_type">Tipo</label>
            <select class="form-select" wire:model.defer="type">
                <option value="">Seleccione</option>
                <option value="extraordinaria">Extraordinaria</option>
                <option value="no extraordinaria">No extraordinaria</option>
                <option value="lobby">Lobby</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-8">
            <label for="for_subject">Asunto</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="subject">
        </fieldset>
    </div>
    
    <div class="row g-3 mb-3">
        <fieldset class="col-12 col-md-4">
            <label for="for-mecanism">Medio</label>
            <select class="form-select" wire:model.defer="mechanism" required>
                <option value="">Seleccionar</option>
                <option value="videoconferencia">Videoconferencia</option>
                <option value="presencial">Presencial</option>
            </select>
        </fieldset>

        <fieldset class="col-12 col-md-2">
            <label for="for-start_at">Hora inicio</label>
            <input type="time" class="form-control" wire:model.defer="start_at" >
        </fieldset>

        <fieldset class="col-12 col-md-2">
            <label for="for-end_at">Hora término</label>
            <input type="time" class="form-control" wire:model.defer="end_at">
        </fieldset>
    </div>

    <hr>

    <h6 class="mt-5"><b>2- Asociaciones de Funcionarios / Federaciones Regionales / Reunión Mesas y Comités de Trabajos</b></h6>

    <div class="row g-3 mb-5">
        <fieldset class="col-12 col-md-4">
            <label for="for-mecanism">Tipo</label>
            <select class="form-select" wire:model.defer="typeGrouping">
                <option value="">Seleccionar</option>
                <option value="asociaciones funcionarios">Asociaciones de Funcionarios</option>
                <option value="federaciones regionales">Federaciones Regionales</option>
                <option value="mesas comites de trabajo">Reunión Mesas y Comités de Trabajos</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_subject">Nombre</label>
            <input class="form-control" type="text" autocomplete="off" wire:model.defer="nameGrouping">
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <button wire:click="addGrouping" class="btn btn-success float-start mt-4" type="button">
                <i class="fas fa-plus"></i> Agregar
            </button>
        </fieldset>
    </div>
    
    @if($groupings)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm small">
                <thead>
                    <tr class="text-center">
                        <th width="10%">#</th>
                        <th width="40%">Tipo</th>
                        <th width="35%">Nombre</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupings as $key => $grouping)
                    <tr>
                        <th class="text-center">{{ $loop->iteration }}</th>
                        <td class="text-center">
                            @switch($grouping['type'])
                                @case('asociaciones funcionarios')
                                    Asociaciones de Funcionarios                              
                                    @break
                                
                                @case('federaciones regionales')
                                    Federaciones Regionales
                                    @break

                                @case('mesas comites de trabajo')
                                    Reunión Mesas y Comités de Trabajos
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $grouping['name'] }}</td>
                        <td class="text-center">
                            <a
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-edit fa-fw"></i> 
                            </a>
                            <a class="btn btn-outline-danger btn-sm"
                                wire:click="deleteGrouping({{ $key }})">
                                <i class="fas fa-trash-alt fa-fw"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <hr>

    <h6 class="mt-5"><b>3- Compromisos</b></h6>
    
    @if($commitments)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm small">
                <thead>
                    <tr class="text-center">
                        <th width="10%">#</th>
                        <th width="40%">Descripción</th>
                        <th width="25%">Usuario / Unidad Organizacional</th>
                        <th width="10%">Fecha límite</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commitments as $key => $commitment)
                    <tr>
                        
                        <th class="text-center">{{ $loop->iteration }}</th>
                        <td style="text-align: justify;">{{ $commitment['description'] }}</td>
                        <td class="text-center">{{ ($commitment['commitment_user_id']) ?  $commitment['commitment_user_name'] : $commitment['commitment_ou_name'] }}</td>
                        <td class="text-center">
                            {{ $commitment['closing_date'] }} <br>
                            @switch($commitment['priority'])
                                @case('normal')
                                    <span class="badge text-bg-success">{{ $commitment['priority'] }}</span>
                                    @break
                                @case('urgente')
                                    <span class="badge text-bg-danger">{{ $commitment['priority'] }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-center">
                            <a href=""
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-edit fa-fw"></i> 
                            </a>
                            <a class="btn btn-outline-danger btn-sm"
                                wire:click="deleteCommitment({{ $key }})">
                                <i class="fas fa-trash-alt fa-fw"></i>
                            </a>
                            @if($commitment['requirement_id'])
                                <a class="btn btn-primary btn-sm" wire:click="showSgr({{ $key }})" target="_blank">
                                    <i class="fas fa-rocket"></i> SGR
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <fieldset class="form-group col-12 col-md-12">
            <label for="for_commitment_description">Descripción</label>
            <textarea class="form-control" rows="3" wire:model.defer="commitmentDescription"></textarea>
        </fieldset>
    </div>

    <div class="row g-3 mb-3">
        <fieldset class="col-12 col-md-4">
            <label for="for-mecanism">Tipo</label>
            <select class="form-select" wire:model="typeResponsible">
                <option value="">Seleccionar</option>
                <option value="individual">Personal</option>
                <option value="ou">Unidad Organizacional</option>
            </select>
        </fieldset>

        <fieldset class="col-12 col-md-4">
            @if($typeResponsible == 'individual')
                <label for="for_user_commitment_id">Nombre Funcionario responsable:</label>
                @livewire('search-select-user', [
                    'selected_id'   => 'user_commitment_id',
                    'required'      => 'required',
                    'emit_name'     => 'searchedCommitmentUser',
                    'user'          => ''
                ])
            @endif

            @if($typeResponsible == 'ou')
                <label for="for_requester_ou_id">Unidad Organizacional</label>
                @livewire('search-select-organizational-unit', [
                    'selected_id'         => 'compromise_ou_id',
                    'required'            => 'required',
                    'emit_name'           => 'searchedCommitmentOu',
                    'organizationalUnit'  => ''
                ])
            @endif
            
            @if($typeResponsible == '' || $typeResponsible == NULL)
                <div class="alert alert-info alert-sm small mt-2" role="alert">
                    Favor seleccionar un tipo de responsabilidad
                </div>
            @endif
        </fieldset>

        <fieldset class="col-12 col-md-2">
            <label for="for-mecanism">Prioridad</label>
            <select class="form-select" wire:model="priority">
                <option value="">Seleccionar</option>
                <option value="normal">Normal</option>
                <option value="urgente">Urgente</option>
            </select>
        </fieldset>

        <fieldset class="col-12 col-sm-2">
            <label for="for_closing_date">Fecha Límite</label>
            <input type="date" class="form-control" wire:model.defer="closingDate">
        </fieldset>
    </div>
    
    <div class="row g-3"> 
        <fieldset class="form-group col-12 col-md-12">
            <button wire:click="addCommitment" class="btn btn-success float-end" type="button">
                <i class="fas fa-plus"></i> Agregar
            </button>
        </fieldset>
    </div>
    
    <hr>

    <div class="row g-3">
        <div class="col-12">
            @if($form == 'create' || (($meetingToEdit && $meetingToEdit->StatusValue != 'Derivado SGR')))
            <button wire:click="save" class="btn btn-primary float-end" type="button">
                <i class="fas fa-save"></i> Guardar
            </button>
            @endif

            @if($meetingToEdit && $meetingToEdit->StatusValue == 'Guardado')
                <button class="btn btn-success float-end me-3" 
                    type="button"
                    wire:click="sentSgr">
                    <i class="fas fa-rocket"></i> Cerrar Reunión
                </button>
            @endif
        </div>
    </div>
</div>
