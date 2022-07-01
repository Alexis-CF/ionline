<fieldset class="form-group col-sm">
    <label for="regiones">Unidad Organizacional / Staff</label>
    <div class="input-group">
        <select class="form-control" id="for_ou_of_performance_id" name="ou_of_performance_id" wire:model="selectedOu" required>
        <option value="">Seleccione...</option>
        @foreach($organizationalUnits as $organizationalUnit)
          <option value="{{ $organizationalUnit->id }}">{{ $organizationalUnit->name }}</option>
          @foreach($organizationalUnit->childs as $child)
            <option value="{{ $child->id }}">{{ $child->name }}</option>
          @endforeach
        @endforeach
        </select>

        <select class="form-control" id="for_replacement_staff_id" name="replacement_staff_id" data-live-search="true" wire:model.defer="selectedReplacementStaff">
            <option value="">Seleccione...</option>
              @if(!is_null($staffManageByOu))
                @foreach($staffManageByOu as $ou)
                  <option value="{{ $ou->replacementStaff->id }}"  {{-- @if($replacementStaff) {{ ($replacementStaff->commune_id == $communeSelected) ? 'selected' : '' }} @endif --}}>{{ $ou->replacementStaff->FullName }}</option>
                @endforeach
              @endif
        </select>
    </div>
</fieldset>
