<div>
    @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        </div>
    @endif


    <h5 class="card-title">Aprobaciones</h5>

    <table class="table table-sm table-bordered small">
        <thead>
            <tr>
                <th scope="col">U.Organizacional</th>
                <th scope="col">Cargo</th>
                <th scope="col">Usuario</th>
                <th scope="col">Tipo</th>
                <th scope="col">Estado</th>
                <th scope="col">Observación</th>
                <th scope="col">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($serviceRequest->SignatureFlows->sortBy('sign_position') as $key => $SignatureFlow)
                <tr>
                    <td>{!! optional($SignatureFlow->user->organizationalUnit)->name ??
                        '<span class="text-danger">SIN UNIDAD ORGANIZACIONAL</span>' !!}</td>
                    <td>{{ $SignatureFlow->user->position }}</td>
                    <td>{{ $SignatureFlow->user->shortName }}</td>
                    <td>
                        @if ($SignatureFlow->sign_position == 1)
                            Responsable
                        @elseif($SignatureFlow->sign_position == 2)
                            Supervisor
                        @else
                            {{ $SignatureFlow->type }}
                        @endif
                    </td>
                    <td class="{{ $SignatureFlow->status ? 'table-success':'table-danger' }}">
                        @if ($SignatureFlow->status === null)
                            <select class="form-control-sm" wire:model.defer="status">
                                <option value="">Seleccionar Estado</option>
                                <option value="1">Aceptar</option>
                                <option value="0">Rechazar</option>
                                <option value="2">Devolver</option>
                            </select>
                        @elseif($SignatureFlow->status === 1)
                            Aceptada
                        @elseif($SignatureFlow->status === 0)
                            Rechazada
                        @elseif($SignatureFlow->status === 2)
                            Devuelta
                        @endif
                    </td>

                    <td>
                        @if ($SignatureFlow->signature_date)
                            {{ $SignatureFlow->observation }}
                        @else
                            <input type="text" class="form-control-sm"
                                value="{{ $SignatureFlow->observation }}" wire:model.defer="observation">
                        @endif
                    </td>
                    <td>
                        @if ($SignatureFlow->signature_date)
                            {{ $SignatureFlow->signature_date }}
                        @else
                            <button class="btn btn-sm btn-primary"
                                wire:click="saveSignatureFlow({{ $SignatureFlow->id }})">Guardar</button>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>