<div>
    <div class="form-row mt-2">
        <fieldset class="form-group col-md-4">
            <label for="type">Tipo de {{ $control->type_format }}</label>
            <input
                type="text"
                class="form-control"
                @if($control->isDispatch())
                    value="{{ optional($control->typeDispatch)->name }}"
                @else
                    value="{{ optional($control->typeReception)->name }}"
                @endif
                id="type"
                readonly
            >
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-md-4">
            <label for="date">Fecha {{ $control->type_format }}</label>
            <input
                type="text"
                class="form-control"
                value="{{ $control->date_format }}"
                id="date"
                readonly
            >
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="program-id">Programa</label>
            <input
                type="text"
                class="form-control"
                value="{{ $control->program_name }}"
                id="program-id"
                readonly
            >
        </fieldset>

        @switch($control->type_dispatch_id)
            @case(\App\Models\Warehouse\TypeDispatch::dispatch())
                <fieldset class="form-group col-md-4">
                    <label for="destination-id">Destino</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($control->destination)->name }}"
                        id="destination-id"
                        readonly
                    >
                </fieldset>
                @break
            @case(\App\Models\Warehouse\TypeDispatch::sendToStore())
                <fieldset class="form-group col-md-4">
                    <label for="store-destination-id">Bodega Destino</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($control->destinationStore)->name }}"
                        id="store-destination-id"
                        readonly
                    >
                </fieldset>
                @break
        @endswitch

        @switch($control->type_reception_id)
            @case(\App\Models\Warehouse\TypeReception::receiving())
                <fieldset class="form-group col-md-4">
                    <label for="origin-id">Origen</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($control->origin)->name }}"
                        id="origin-id"
                        readonly
                    >
                </fieldset>
                @break
            @case(\App\Models\Warehouse\TypeReception::receiveFromStore())
                <fieldset class="form-group col-md-4">
                    <label for="store-origin-id">Bodega Origen</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($control->originStore)->name }}"
                        id="store-origin-id"
                        readonly
                    >
                </fieldset>
                @break
            @case(\App\Models\Warehouse\TypeReception::purchaseOrder())
                <fieldset class="form-group col-md-4">
                    <label for="purchase-order-code">Código OC</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $control->po_code }}"
                        id="purchase-order-code"
                        readonly
                    >
                </fieldset>
                @break
        @endswitch
    </div>

    @if($control->isPurchaseOrder())
        <div class="form-row">
            <fieldset class="form-group col-md-4">
                <label for="po-date">Fecha OC</label>
                <input
                    type="text"
                    id="po-date"
                    class="form-control"
                    value="{{ $control->po_date }}"
                    readonly
                >
            </fieldset>
            <fieldset class="form-group col-md-8">
                <label for="supplier-name">Proveedor</label>
                <input
                    type="text"
                    id="supplier-name"
                    class="form-control"
                    value="{{ optional($control->supplier)->name }}"
                    readonly
                >
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group col-md-3">
                <label for="guide-date">Fecha Guía</label>
                <input
                    type="date"
                    id="guide-date"
                    class="form-control"
                    value="{{ $control->guide_date }}"
                    readonly
                >
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="guide-number">Número Guía</label>
                <input
                    type="text"
                    id="guide-number"
                    class="form-control"
                    value="{{ $control->guide_number }}"
                    readonly
                >
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="invoice-date">Fecha Factura</label>
                <input
                    type="date"
                    id="invoice-date"
                    class="form-control"
                    value="{{ $control->invoice_date }}"
                    readonly
                >
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="invoice-number">Número Factura</label>
                <input
                    type="text"
                    id="invoice-number"
                    class="form-control"
                    value="{{ $control->invoice_date }}"
                    readonly
                >
            </fieldset>
        </div>
    @endif

    <div class="form-row">
        <fieldset class="form-group col-md-12">
            <label for="note">Nota</label>
            <input
                type="text"
                class="form-control"
                value="{{ $control->note }}"
                id="note"
                readonly
            >
        </fieldset>
    </div>
</div>
