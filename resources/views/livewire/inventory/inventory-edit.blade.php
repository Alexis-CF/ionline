<div>
    @section('title', 'Editar Inventario')

    @include('inventory.nav', [
        'establishment' => $establishment
    ])

    @include('layouts.bt4.partials.flash_message')

    <div class="row">
        <div class="col">
            <h3 class="mb-3">
                Detalle del ítem
            </h3>
        </div>
        <div class="col text-right">
            <a
                href="{{ route('inventories.pending-inventory', [
                    'establishment' => $establishment
                ]) }}"
                class="btn btn-primary"
            >
                Atrás
            </a>
        </div>
    </div>

    <div class="form-row mb-3">
        <fieldset class="col-md-2">
            <label for="code" class="form-label">
                Código
            </label>
            <input
                type="text"
                class="form-control"
                id="code"
                value="{{ $inventory->unspscProduct->code }}"
                disabled
                readonly
            >
        </fieldset>

        <fieldset class="col-md-3">
            <label for="product" class="form-label">
                Producto <small>(Artículo)</small>
            </label>
            <input
                type="text"
                class="form-control"
                id="product"
                value="{{ $inventory->unspscProduct->name }}"
                disabled
                readonly
            >
        </fieldset>

        <fieldset class="col-md-7">
            <label for="description" class="form-label">
                Descripción <small>(especificación técnica)</small>
            </label>
            <input
                type="text"
                class="form-control"
                id="description"
                value="{{ $inventory->product ? $inventory->product->name : $inventory->description }}"
                disabled
                readonly
            >
        </fieldset>
    </div>

    <div class="form-row mb-3">
        <fieldset class="col-md-2">
            <label for="number-inventory" class="form-label">
                Nro. Inventario
            </label>
            <input
                type="text"
                class="form-control @error('number_inventory') is-invalid @enderror"
                id="number-inventory"
                wire:model.defer="number_inventory"
                autocomplete="off"
            >
            @error('number_inventory')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>

        <fieldset class="col-md-3">
            <label for="brand" class="form-label">
                Marca
            </label>
            <input
                type="text"
                class="form-control @error('brand') is-invalid @enderror"
                id="brand"
                wire:model.defer="brand"
                autocomplete="off"
            >
            @error('brand')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>

        <fieldset class="col-md-2">
            <label for="model" class="form-label">
                Modelo
            </label>
            <input
                type="text"
                class="form-control @error('model') is-invalid @enderror"
                id="model"
                wire:model.defer="model"
                autocomplete="off"
            >
            @error('model')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>

        <fieldset class="col-md-3">
            <label for="serial-number" class="form-label">
                Número de Serie
            </label>
            <input
                type="text"
                class="form-control @error('serial_number') is-invalid @enderror"
                id="serial-number"
                wire:model.defer="serial_number"
                autocomplete="off"
            >
            @error('serial_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>

        <fieldset class="col-md-2">
            <label for="status" class="form-label">
                Estado
            </label>
            <select
                class="form-control @error('status') is-invalid @enderror"
                id="status"
                wire:model="status"
                >
                <option value="">Seleccione un estado</option>
                <option value="1">Bueno</option>
                <option value="0">Regular</option>
                <option value="-1">Malo</option>
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </fieldset>



    </div>

    <div class="form-row mb-3">
        <fieldset class="col-md-2">
            <label for="useful-life" class="form-label">
                Vida útil
            </label>
            <input
                type="text"
                class="form-control @error('useful_life') is-invalid @enderror"
                id="useful-life"
                wire:model.defer="useful_life"
                autocomplete="off"
            >
            @error('useful_life')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>

        <fieldset class="col-md-3">
            <label for="depreciation" class="form-label">
                Depreciación
            </label>
            <input
                type="text"
                class="form-control @error('depreciation') is-invalid @enderror"
                id="depreciation"
                wire:model.defer="depreciation"
                autocomplete="off"
            >
            @error('depreciation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>

        <fieldset class="col-md-2">
            <label for="cost-center" class="form-label">
                Cuenta contable
            </label>
            <select
                type="text"
                class="form-control @error('accounting_code_id') is-invalid @enderror"
                id="cost-center"
                wire:model="accounting_code_id"
            >
                <option value="">Seleccione cuenta contable</option>
                @foreach($accountingCodes as $accountingCode)
                    <option value="{{ $accountingCode->id }}">
                    {{ $accountingCode->id }} - {{ $accountingCode->description }}
                    </option>
                @endforeach
            </select>
            @error('accounting_code_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>
    </div>

    <div class="form-row mb-3">
        @if($inventory->control && $inventory->control->requestForm)
            <fieldset class="col-md-3">
                <label for="date-reception" class="form-label">
                    Formulario Requerimiento
                </label>
                <br>
                <a
                    class="btn btn-primary btn-block"
                    href="{{ route('request_forms.show', $inventory->control->requestForm) }}"
                    target="_blank"
                >
                    <i class="fas fa-file-alt"></i> #{{ $inventory->control->requestForm->id }}
                </a>
            </fieldset>
        @endif

        @if($inventory->po_id)
            <fieldset class="col-md-2">
                <label for="oc" class="form-label">
                    OC
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="oc"
                    value="{{ $inventory->po_code }}"
                    disabled
                    readonly
                >
            </fieldset>

            <fieldset class="col-md-2">
                <label for="date-oc" class="form-label">
                    Fecha OC
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="date-oc"
                    value="{{ $inventory->po_date }}"
                    disabled
                    readonly
                >
            </fieldset>

            <fieldset class="col-md-2">
                <label for="value-oc" class="form-label">
                    Valor
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="value-oc"
                    value="${{ money($inventory->po_price) }}"
                    disabled
                    readonly
                >
            </fieldset>
        @endif

        @if($inventory->control && $inventory->control->requestForm)
            <fieldset class="col-md-3">
                <label for="financing" class="form-label">
                    Programa
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="financing"
                    value="{{ $inventory->control->requestForm->associateProgram->name }}"
                    disabled
                >
            </fieldset>
        @endif

    </div>


    <div class="form-row mb-3">

        @if($inventory->control)
            <fieldset class="col-md-5">
                <label for="supplier" class="form-label">
                    @if($inventory->control->isPurchaseOrder())
                        Proveedor
                    @else
                        Origen
                    @endif
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="supplier"
                    @if($inventory->control->isPurchaseOrder())
                        value="{{ $inventory->purchaseOrder->supplier_name }}"
                    @else
                        value="{{ $inventory->control->origin->name }}"
                    @endif
                    disabled
                >
            </fieldset>
        @endif

        <fieldset class="col-md-2">
            <label for="dte-number" class="form-label">
                Número Factura
            </label>
            <input
                type="text"
                class="form-control"
                id="dte-number"
                value="{{ $inventory->dte_number }}"

            >
        </fieldset>

        @if($inventory->control && $inventory->control->invoice_url)
            <fieldset class="col-md-2">
                <label for="date-reception" class="form-label">
                    Factura
                </label>
                <br>
                <a
                    class="btn btn-primary btn-block"
                    href="{{ asset($inventory->control->invoice_url) }}"
                    target="_blank"
                >
                    <i class="fas fa-eye"></i> Ver archivo
                </a>
            </fieldset>
        @endif

        @if($inventory->control)
            <fieldset class="col-md-2">
                <label for="date-reception" class="form-label">
                    Ingreso bodega
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="date-reception"
                    value="{{ $inventory->control->date->format('Y-m-d') }}"
                    disabled
                    readonly
                >
            </fieldset>
        @endif
    </div>

    @if($inventory->po_code)
        <div >
            <h5 class="mt-3">Facturas relacionadas con la OC</h5>
            @livewire('warehouse.invoices.list-invoices', ['inventory' => $inventory])
        </div>
    @endif

    <div class="form-row mb-3">
        <fieldset class="col-md-12">
            <label for="observations" class="form-label">
                Observaciones
            </label>
            <textarea
                id="observations"
                cols="30"
                rows="4"
                class="form-control @error('observations') is-invalid @enderror"
                wire:model.debounce.1500ms="observations"
            >
            </textarea>
            @error('observations')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </fieldset>
    </div>

    <div class="form-row mb-3">
        <div class="col text-right">
            <button class="btn btn-primary" wire:click="update">
                <i class="fas fa-save"></i> Actualizar
            </button>
        </div>
    </div>

    <h5 class="mt-3">Registrar nuevo traslado y solicitud de recepción</h5>

    @livewire('inventory.register-movement', ['inventory' => $inventory ])

    @livewire('inventory.update-movement', ['inventory' => $inventory])

    <h5 class="mt-3">Registrar baja del ítem</h5>

    @livewire('inventory.add-discharge-date', ['inventory' => $inventory])

    <div class="row">
        <div class="col">
            <h5 class="mt-3">Historial del ítem</h5>
            @livewire('inventory.movement-index', ['inventory' => $inventory])
        </div>

    </div>

</div>
