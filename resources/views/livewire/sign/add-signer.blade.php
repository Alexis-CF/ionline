<div>
    <div class="form-row">
        <div class="form-group col-12">
            <label for="establishment-id">Establecimiento</label>
            <select
                class="custom-select"
                id="establishment-id"
                wire:model="establishment_id"
            >
                <option value="">Seleccione establecimiento</option>
                @foreach($establishments->sortBy('name') as $establishment)
                    <option value="{{ $establishment->id }}"> {{ $establishment->official_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-12">
            <label for="ou">Unidad Organizacional</label>
            <select
                class="custom-select"
                wire:model="organizational_unit_id"
                style="font-family:monospace;"
                id="ou"
            >
                <option value="">Seleccione unidad</option>
                @foreach($ous as $id => $ou)
                    <option value="{{ $id }}">{{ $ou }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-12">
            <label for="user-id">Usuarios</label>
            <div class="input-group">
                <select
                    class="custom-select"
                    title="Seleccione usuario"
                    wire:model="user_id"
                >
                    <option value="">Seleccione un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->short_name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button
                        class="btn btn-primary"
                        wire:target="add"
                        wire:loading.attr="disabled"
                        wire:click="add"
                        title="Agregar"
                        @if($users->isEmpty()) disabled @endif
                    >
                        <span
                            class="spinner-border spinner-border-sm"
                            role="status"
                            wire:loading
                            wire:target="add"
                            aria-hidden="true"
                        >
                        </span>

                        <span
                            wire:loading.remove
                            wire:target="add"
                        >
                            <i class="fas fa-plus"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
