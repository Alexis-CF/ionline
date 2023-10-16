<div>
    <h3>Bloqueo de hospedaje</h3>
    
    <div class="form-row">

        <fieldset class="form-group col-2">
            <label for="for_hotel_id">F.Desde</label>
            <input type="date" class="form-control" id="for_start_date" name="start_date" wire:model="start_date">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_hotel_id">F.Hasta</label>
            <input type="date" class="form-control" id="for_end_date" name="end_date" wire:model="end_date">
        </fieldset>

        <fieldset class="form-group col-1">
            <label for="for_hotel_id"><br></label>
            <button class="form-control btn btn-primary" wire:click="save()">Guardar</button>
        </fieldset>

    </div>

    <!-- Mensaje de éxito -->
    @include('layouts.bt5.partials.flash_message')
</div>
