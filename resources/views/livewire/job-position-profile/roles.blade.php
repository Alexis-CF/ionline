<div>
    
    @if($roles->count() > 0)
        <div class="form-row">
            <div class="col-12 col-md-3 mt-2">
                <h6><i class="fas fa-list-ol"></i> Listado de Funciones</h6> 
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr class="text-center table-info">
                        <th width="7%">#</th>
                        <th>Descripción</th>
                        <th width="14%" colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <th class="text-center">{{ $loop->iteration }}</th>
                        <td>
                            @if($editRoleIdRender != $role->id)
                                {{ $role->description }}
                            @else
                            <div class="form-row">
                                <fieldset class="form-group col-md">
                                    <input type="text" class="form-control" name="description" id="for_description" wire:model.live="description" required>
                                </fieldset>
                                <fieldset class="form-group col-md-1">
                                    <a class="btn btn-primary" wire:click="saveEditRole({{$role}})"><i class="fas fa-save"></i></a>
                                </fieldset>
                                <fieldset class="form-group col-md-2">
                                    <a class="btn btn-danger btn-block" wire:click="cancelEdit()">Cancelar</a>
                                </fieldset>
                            </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <a class="btn btn-secondary btn-sm"
                                wire:click="editRole({{ $role }})">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-danger btn-sm"
                                wire:click="deleteRole({{ $role }})"
                                onclick="return confirm('¿Está seguro que desea eliminar la función?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="form-row">
            <div class="col-12">
                <button class="btn text-white btn-info float-right" wire:click.prevent="add({{$i}})"><i class="fas fa-plus"></i> Agregar</button>
            </div>
        </div>
    @else
        <div class="form-row">
            <div class="col-12 col-md-3 mt-2">
                <h6><i class="fas fa-list-ol"></i> Listado de Funciones</h6> 
            </div>
            <div class="col-12 col-md-9">
                <button class="btn text-white btn-info" wire:click.prevent="add({{$i}})"><i class="fas fa-plus"></i> Agregar</button>
            </div>
        </div>
    @endif

    <br>

    {{--
    @if($messageMaxRoles != NULL)
        <div class="alert alert-danger" role="alert">
            {{ $messageMaxRoles }}
        </div>
    @endif
    --}}

    @foreach($inputs as $key => $value)
        <div class="form-row">
            <fieldset class="form-group col">
                <label for="for_roles_name">Función</label>
                <input type="text" class="form-control" name="descriptions[]" id="for_description" wire:key="value-{{ $value }}" placeholder="" required>
            </fieldset>

            <fieldset class="form-group col-md-2">
                <label for="for_button"><br></label>
                <button class="btn btn-danger btn-block" wire:click.prevent="remove({{$key}})">Eliminar</button>
            </fieldset>
        </div>
    @endforeach
</div>