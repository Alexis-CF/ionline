<div>
    <label for="for_position">Cargo / Función</label>
    <input class="form-control" type="text" autocomplete="off" wire:model="position" required>
    @error('position') <span class="text-danger error small">{{ $message }}</span> @enderror
</div>
