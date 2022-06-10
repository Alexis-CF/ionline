<div>
    <div class="row">
        <div class="col my-2">
            <h5>
                Listado de
                @if($type == 'receiving')
                    Ingresos:
                @else
                    Egresos:
                @endif
                 {{ $store->name }}
            </h5>
        </div>

        <div class="col text-right">
            @if($type == 'receiving')
                @if($store)
                <div class="dropdown">
                    <button
                        class="btn btn-primary dropdown-toggle"
                        type="button"
                        id="control-reception"
                        data-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="fas fa-plus"></i> Nuevo Ingreso
                    </button>
                    <div class="dropdown-menu" aria-labelledby="control-reception">
                        <a
                            class="dropdown-item"
                            href="{{ route('warehouse.controls.create', [
                                'store' => $store,
                                'type' => 'receiving'
                            ]) }}"
                        >
                            <i class="fas fa-download"></i> Ingreso Normal
                        </a>
                        <a
                            class="dropdown-item"
                            href="{{ route('warehouse.generate-reception', [
                                'store' => $store,
                            ]) }}"
                        >
                            <i class="fas fa-shopping-cart"></i> Ingreso Orden de Compra
                        </a>
                    </div>
                </div>

                @endif
            @else
                @if($store)
                <a
                    class="btn btn-primary"
                    href="{{ route('warehouse.controls.create', [
                        'store' => $store,
                        'type' => 'dispatch'
                    ]) }}"
                >
                    <i class="fas fa-plus"></i> Nuevo Egreso
                </a>
                @endif
            @endif
        </div>
    </div>

    <div class="table table-responsive">
        <table class="table table-sm table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Fecha</th>
                    <th>
                        @if($type == 'receiving')
                            Origen
                        @else
                            Destino
                        @endif
                    </th>
                    <th>Programa</th>
                    <th class="text-center"># Productos</th>
                    <th class="text-center">Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="d-none" wire:loading.class.remove="d-none" wire:target="search">
                    <td class="text-center" colspan="8">
                        @include('layouts.partials.spinner')
                    </td>
                </tr>
                @forelse($controls as $control)
                <tr>
                    <td class="text-center">
                        <a
                            href="{{ route('warehouse.controls.edit', [
                                'store' => $store,
                                'control' => $control
                            ]) }}"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            <i class="fas fa-edit"></i> {{ $control->id }}
                        </a>
                    </td>
                    <td>{{ $control->date_format }}</td>
                    <td>
                        @if($control->isDispatch())
                            @switch($control->type_dispatch_id)
                                @case(\App\Models\Warehouse\TypeDispatch::dispatch())
                                    {{ optional($control->destination)->name }}
                                    @break
                                @case(\App\Models\Warehouse\TypeDispatch::adjustInventory())
                                    {{ optional($control->typeDispatch)->name }}
                                    @break
                                @case(\App\Models\Warehouse\TypeDispatch::sendToStore())
                                    {{ optional($control->destinationStore)->name }}
                                    @break
                            @endswitch
                            <br>
                            <small>
                                {{ optional($control->typeDispatch)->name }}
                            </small>
                        @else
                            @switch($control->type_reception_id)
                                @case(\App\Models\Warehouse\TypeReception::receiving())
                                    {{ optional($control->origin)->name }}
                                    @break
                                @case(\App\Models\Warehouse\TypeReception::receiveFromStore())
                                    {{ optional($control->originStore)->name }}
                                    @break
                                @case(\App\Models\Warehouse\TypeReception::return())
                                    {{ optional($control->originStore)->name }}
                                    @break
                                @case(\App\Models\Warehouse\TypeReception::purchaseOrder())
                                    {{ $control->po_code }}
                                    @break
                            @endswitch
                            <br>
                            <small>
                                {{ optional($control->typeReception)->name }}
                            </small>
                        @endif
                    </td>
                    <td>{{ $control->program_name }}</td>
                    <td class="text-center">{{ $control->items->count() }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $control->color_confirm }}">
                            {{ $control->confirm_format }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a
                            href="{{ route('warehouse.control.pdf', [
                                'store' => $store,
                                'control' => $control
                            ]) }}"
                            class="btn btn-sm btn-outline-secondary"
                            target="_blank"
                        >
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="text-center">
                    <td colspan="8">
                        <em>No hay resultados</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <caption>
                Total de resultados: {{ $controls->total() }}
            </caption>
        </table>
    </div>

    {{ $controls->links() }}
</div>
