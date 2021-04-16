<div>
  <div class="card" id="control_turnos">
    <div class="card-header">
      Control de Turnos
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">
        <div class="form-row">
          <fieldset class="form-group col-3">
              <label for="for_estate">Entrada</label>
              <input type="date" class="form-control" wire:model="shift_start_date" id="shift_start_date">
          </fieldset>
          <fieldset class="form-group col">
              <label for="for_estate">Hora</label>
              <input type="time" class="form-control" wire:model="start_hour" id="start_hour">
          </fieldset>
          <fieldset class="form-group col-3">
              <label for="for_estate">Salida</label>
              <input type="date" class="form-control" wire:model="shift_end_date" id="shift_end_date">
          </fieldset>
          <fieldset class="form-group col">
              <label for="for_estate">Hora</label>
              <input type="time" class="form-control" wire:model="end_hour" id="end_hour">
          </fieldset>
          <fieldset class="form-group col">
              <label for="for_estate">Observación</label>
              <input type="text" class="form-control" wire:model="observation" id="observation">
          </fieldset>
          <fieldset class="form-group col">
              <label for="for_estate"><br/></label>

              @can('Service Request: fulfillments rrhh')
                <button type="button" class="btn btn-primary form-control add-row" wire:click="save()">Ingresar</button>
              @endcan

              @can('Service Request: fulfillments responsable')
                @if($fulfillment->responsable_approbation == null)
                  <button type="button" class="btn btn-primary form-control add-row" wire:click="save()">Ingresar</button>
                @endif
              @endcan

          </fieldset>
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
              @foreach($fulfillment->shiftControls->sortBy('start_date') as $key => $shiftControl)
                <tr>

                  <td>{{$shiftControl->start_date->format('Y-m-d H:i')}}</td>
                  <td>{{$shiftControl->end_date->format('Y-m-d H:i')}}</td>
                  <td>{{$shiftControl->observation}}</td>
                  <td>

                    @can('Service Request: fulfillments rrhh')
                    <button type="submit" class="btn btn-outline-secondary btn-sm" onclick="return confirm('¿Está seguro de eliminar la información?');" wire:click="delete({{$shiftControl}})">
                    <span class="fas fa-trash-alt" aria-hidden="true"></span>
                    </button>
                    @endcan

                    @can('Service Request: fulfillments responsable')
                      @if($fulfillment->responsable_approbation == null)
                      <button type="submit" class="btn btn-outline-secondary btn-sm" onclick="return confirm('¿Está seguro de eliminar la información?');" wire:click="delete({{$shiftControl}})">
                      <span class="fas fa-trash-alt" aria-hidden="true"></span>
                      </button>
                      @endif
                    @endcan

                  </td>
                </tr>
              @endforeach
            </tbody>
        </table>

      </li>
    </ul>
  </div>

  <div wire:loading wire:target="save">
      <span class="text-muted small">Procesando...</span>
  </div>
</div>
