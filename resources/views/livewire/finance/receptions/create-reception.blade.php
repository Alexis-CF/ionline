<div>
    <h3 class="mb-3">Crear un acta de recepción conforme</h3>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ active('finance.receptions.create') }}"
                aria-current="page"
                href="{{ route('finance.receptions.create') }}">Con Orden de Compra</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
                href="#">Sin Orden de Compra</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ active('finance.receptions.type') }}"
                href="{{ route('finance.receptions.type') }}">Tipos de Acta</a>
        </li>
    </ul>

    <div class="row mb-3 g-2">
        <div class="col-md-3">
            <label for="reception-date">Orden de compra</label>
            <div class="input-group">
                <input type="text"
                    class="form-control"
                    placeholder="Orden de compra"
                    aria-label="Orden de compra"
                    aria-describedby="purchase-order"
                    wire:model.debounce="reception.purchase_order">
                <button class="btn btn-outline-primary"
                    wire:click="getPurchaseOrder">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        @if($purchaseOrder)
        <div class="col-md-3 text-center">
            <b>Formulario de Requerimiento</b><br>
            @if ($purchaseOrder->requestForm)
                <a href="{{ route('request_forms.show', $purchaseOrder->requestForm->id) }}"
                    target="_blank">
                    {{ $purchaseOrder->requestForm->folio }}
                </a>
            @else
                <span class="text-danger">
                    La Orden de Compra no está registrada 
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-biohazard"></i> Notificar a Abastecimiento
                    </button>
                </span>
            @endif
        </div>
        <div class="col-md-3 text-center">
            <b>Actas creadas para esta OC</b><br>
            <ul>
                <li>
                    <a href="#">Acta ID: 2123 fecha: 2023-11-01</a>
                </li>
                <li>
                    <a href="#">Acta ID: 2234 fecha: 2023-11-02</a>
                </li>
            </ul>
        </div>
        <div class="col-md-3 text-center">
            <b>Facturas</b><br>
            <ul>
                @foreach ($purchaseOrder->dtes as $dte)
                    <li>
                        <a href="#">{{ $dte->id }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        @elseif(is_null($purchaseOrder))
        <div class="col-md-3 text-center">
            <br>
            <span class="text-danger">No se encontró la orden de compra</span>
        </div>
        @endif

    </div>


    @if ($purchaseOrder)

        <h4>Recepción</h4>
        <div class="row mb-3 g-2">
            <div class="col-4">
                <div class="form-group">
                    <label for="form-reception-typeto ">Tipo de acta</label>
                    <select class="form-select"
                        wire:model="reception.reception_type_id">
                        <option value=""></option>
                        @foreach ($types as $id => $type)
                            <option value="{{ $id }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('reception.reception_type_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                        type="radio"
                        name="partial_reception"
                        wire:model.defer="reception.partial_reception"
                        id="partial_reception_partial"
                        value="1">
                    <label class="form-check-label"
                        for="for-parcial">Recepcionar la OC Parcial</label>
                    <div class="form-text">&nbsp;</div>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                        type="radio"
                        name="partial_reception"
                        wire:model.defer="reception.partial_reception"
                        id="partial_reception_complete"
                        value="0">
                    <label class="form-check-label"
                        for="for-completa">Recepcionar la OC Completa</label>
                    <div class="form-text">&nbsp;</div>
                </div>
                <div class="form-check form-switch form-check-inline">
                    <input class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="for-order_completed"
                        wire:model.defer="reception.order_completed">
                    <label class="form-check-label"
                        for="flexSwitchCheckDefault">Marcar la Orden de Compra como Completada</label>
                    <div class="form-text">No se recibirán más items de esta Orden de Compra</div>
                </div>
                <div class="form-check form-switch form-check-inline float-end">
                    <input class="form-check-input"
                        type="checkbox"
                        role="switch"
                        wire:model.defer="reception.cenabast"
                        id="for-cenabaste">
                    <label class="form-check-label"
                        for="flexSwitchCheckDefault">Cenabast</label>
                </div>

            </div>
        </div>

        <div class="row mb-3 g-2">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="number">Número de acta</label>
                    <input type="text"
                        class="form-control"
                        wire:model="reception.number">
                    <div class="form-text">Dejar en blanco para autogenerar</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="reception-date">Fecha Recepción</label>
                    <input type="date"
                        class="form-control"
                        wire:model="reception.date">
                    @error('reception.date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-2 offset-md-2">
                <div class="form-group">
                    <label for="reception-date">Tipo documento</label>
                    <select name="document_type"
                        id="document_type"
                        class="form-select"
                        wire:model="reception.doc_type">
                        <option></option>
                        <option>Guía de despacho</option>
                        <option>Factura</option>
                        <option>Boleta</option>
                        <option>Boleta Honorarios</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="reception-date">Número documento</label>
                    <input type="text"
                        class="form-control"
                        wire:model.debounce.500ms="reception.doc_number">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="reception-date">Fecha documento</label>
                    <input type="date"
                        class="form-control"
                        wire:model="reception.doc_date">
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group">
                    <Label>Encabezado</Label>
                    <textarea name=""
                        id="for-header_notes"
                        rows="5"
                        class="form-control"
                        wire:model.debounse.500ms="reception.header_notes"></textarea>
                    <div>Plantillas: [ compra servicios ] [ boleta agua ] <i class="fas fa-cog"></i> </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Cantidad / Unidad</th>
                    <th>Especificaciones Comprador</th>
                    <th>Especificaciones Proveedor</th>
                    <th>Precio Unitario</th>
                    <th>Descuento</th>
                    <th>Cargos</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->json->Listado[0]->Items->Listado as $item)
                    <tr class="table-secondary ">
                        <td class="text-center">
                            {{ $item->CodigoCategoria }}
                        </td>
                        <td>{{ $item->Producto }}</td>
                        <td style="text-align: right;">
                            {{ $item->Cantidad }} {{ $item->Unidad }}
                            <div class="input-group">

                                <select name=""
                                    id=""
                                    class="form-select">
                                    <option value=""></option>
                                    @for ($i = 1; $i <= $item->Cantidad; $i++)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>
                                <button class="btn btn-sm btn-primary form-control">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td>{{ $item->EspecificacionComprador }}</td>
                        <td>{{ $item->EspecificacionProveedor }}</td>
                        <td style="text-align: right;">{{ money($item->PrecioNeto) }}</td>
                        <td style="text-align: right;">{{ money($item->TotalDescuentos) }}</td>
                        <td style="text-align: right;">{{ money($item->TotalCargos) }}</td>
                        <td style="text-align: right;">{{ money($item->Cantidad * $item->PrecioNeto) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Acta 2123
                        </td>
                        <td>
                            50
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group">
                    <Label>Observaciones</Label>
                    <textarea name=""
                        id="for-footer_notes"
                        rows="5"
                        class="form-control"
                        wire:model.debounce.500ms="reception.footer_notes"></textarea>
                </div>
            </div>
        </div>

        <h4 class="mb-2">Firmantes</h4>
        <div class="row mb-3">
            <div class="col-7">
                <label for="forOrganizationalUnit">Establecimiento / Unidad Organizacional</label>
                @livewire('select-organizational-unit', [
                    'emitToListener' => 'ouSelected',
                ])
                <b>Autoridad: </b>
                @if (is_null($authority))
                    <span class="text-danger">La unidad organizacional no tiene una autoridad definida</span>
                @else
                    {{ $authority }}
                @endif
            </div>
            <div class="col-1 text-center">
                <br>
                O
            </div>
            <div class="col">
                <label for="forUsers">Usuario</label>
                @livewire('search-select-user')
            </div>
        </div>
        <div class="row text-center">
            <div class="col">
                <b>Columna Izquierda <span class="text-danger">☭</span></b>
                <div class="row mt-1 mb-2 g-2">
                    <div class="col">
                        <button class="btn btn-primary form-control"
                            wire:click="addApproval('left')"
                            @disabled(is_null($signer_id) and is_null($signer_ou_id))>
                            <i class="bi bi-plus"></i>
                            Agregar firmante
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-success form-control"
                            wire:click="addApproval('left',{{ auth()->id() }})">
                            <i class="bi bi-plus"></i>
                            Agregarme a mi
                        </button>
                    </div>
                </div>

                <div style="height: 40px">
                    @if (array_key_exists('left', $this->approvals))
                        {{ $this->approvals['left']['signerShortName'] }}
                        @if (array_key_exists('sent_to_ou_id', $approvals['left']))
                            <i class="fas fa-chess-king"></i>
                        @else
                            <i class="fas fa-chess-pawn"></i>
                        @endif
                        <button class="btn btn-sm btn-danger"
                            wire:click="removeApproval('left')">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </div>

            </div>
            <div class="col">
                <b>Columna Central</b>
                <div class="row mt-1 mb-2 g-2">
                    <div class="col">
                        <button class="btn btn-primary form-control"
                            wire:click="addApproval('center')"
                            @disabled(is_null($signer_id) and is_null($signer_ou_id))>
                            <i class="bi bi-plus"></i>
                            Agregar firmante
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-success form-control"
                            wire:click="addApproval('center',{{ auth()->id() }})">
                            <i class="bi bi-plus"></i>
                            Agregarme a mi
                        </button>
                    </div>
                </div>

                <div style="height: 40px">
                    @if (array_key_exists('center', $this->approvals))
                        {{ $this->approvals['center']['signerShortName'] }}
                        @if (array_key_exists('sent_to_ou_id', $approvals['center']))
                            <i class="fas fa-chess-king"></i>
                        @else
                            <i class="fas fa-chess-pawn"></i>
                        @endif
                        <button class="btn btn-sm btn-danger"
                            wire:click="removeApproval('center')">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </div>
            </div>


            <div class="col">
                <b>Columna Derecha</b>
                <div class="row mt-1 mb-2 g-2">
                    <div class="col">
                        <button class="btn btn-primary form-control"
                            wire:click="addApproval('right')"
                            @disabled(is_null($signer_id) and is_null($signer_ou_id))>
                            <i class="bi bi-plus"></i>
                            Agregar firmante
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-success form-control"
                            wire:click="addApproval('right',{{ auth()->id() }})">
                            <i class="bi bi-plus"></i>
                            Agregarme a mi
                        </button>
                    </div>
                </div>

                <div style="height: 40px">
                    @if (array_key_exists('right', $this->approvals))
                        {{ $this->approvals['right']['signerShortName'] }}
                        @if (array_key_exists('sent_to_ou_id', $approvals['right']))
                            <i class="fas fa-chess-king"></i>
                        @else
                            <i class="fas fa-chess-pawn"></i>
                        @endif
                        <button class="btn btn-sm btn-danger"
                            wire:click="removeApproval('right')">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </div>
            </div>

        </div>



        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="">Adjuntar otros documentos</label>
                    <input type="file"
                        name=""
                        id=""
                        class="form-control">
                </div>
            </div>
        </div>

        <hr>

        <!----------------------------------->
        <!-- Preview del acta de recepción -->
        <!----------------------------------->
        <div class="row mb-3">
            <div class="col-9">
                <img src="{{ asset('/images/logo_rgb_' . auth()->user()->organizationalUnit->establishment->alias . '.png') }}"
                    height="109"
                    alt="Logo de la institución">
            </div>
            <div class="col-3 align-self-end fs-5">
                <table class="table">
                    <tr>
                        <th>Número: </th>
                        <td>
                            @if ($reception->number)
                                {{ $reception->number }}
                            @else
                                <i>Autogenerado</i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Fecha:
                        </th>
                        <td>
                            @if ($reception->date)
                                {{ $reception->date?->format('d-m-Y') }}
                            @else
                                <span class="text-danger">Falta la fecha</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <br>

        <h3 class="text-center mb-3">Acta de recepción conforme</h3>

        <p style="white-space: pre-wrap;">{{ $reception->header_notes }}</p>

        <table class="table table-sm table-bordered">
            <tr>
                <th>
                    Orden de Compra
                </th>
                <td>
                    {{ $reception->purchase_order }}
                </td>
                <th>
                    Proveedor
                </th>
                <td>
                    {{ $purchaseOrder->json->Listado[0]->Proveedor->Nombre }}
                </td>
                <th>
                    RUT Proveedor
                </th>
                <td>
                    {{ $purchaseOrder->json->Listado[0]->Proveedor->RutSucursal }}
                </td>
            </tr>
            <tr>
                <th>
                    N° Documento
                </th>
                <td>
                    {{ $reception->doc_number }}
                </td>
                <th>
                    Tipo de documento
                </th>
                <td>
                    {{ $reception->doc_type }}
                </td>
                <th>
                    Fecha Emisón:
                </th>
                <td>
                    {{ $reception->doc_date?->format('d-m-Y') }}
                </td>
            </tr>
        </table>

        <table class="table table-sm table-bordered mb-3">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Cantidad / Unidad</th>
                    <th>Especificaciones Comprador</th>
                    <th>Especificaciones Proveedor</th>
                    <th>Precio Unitario</th>
                    <th>Descuento</th>
                    <th>Cargos</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <p style="white-space: pre-wrap;">{{ $reception->footer_notes }}</p>
        <br>
        <br>
        <br>

        <div class="row text-center mt-3">
            <div class="col">
                @if (array_key_exists('left', $approvals))
                    <b>{{ $this->approvals['left']['signerShortName'] }}</b><br>
                    {{ auth()->user()->organizationalUnit->establishment->name }}<br>
                @endif
            </div>
            <div class="col">
                @if (array_key_exists('center', $approvals))
                    <b>{{ $this->approvals['center']['signerShortName'] }}</b><br>
                    {{ auth()->user()->organizationalUnit->establishment->name }}<br>
                @endif
            </div>
            <div class="col">
                @if (array_key_exists('right', $approvals))
                    <b>{{ $approvals['right']['signerShortName'] }}</b><br>
                    {{ auth()->user()->organizationalUnit->establishment->name }}<br>
                @endif
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-end">
                <button class="btn btn-primary"
                    wire:click="save">Crear</button>
            </div>
        </div>
    @endif
</div>
