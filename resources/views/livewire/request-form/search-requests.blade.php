<div>
    <div class="card card-body small">
        <h5 class="mb-3"><i class="fas fa-search"></i> Buscar:</h5>
        <div class="form-row">
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_status_search">Estado Formulario</label>
                <select name="status_search" class="form-control form-control-sm" wire:model.debounce.500ms="selectedStatus" @if($inbox == 'purchase') disabled @endif>
                    <option value="">Seleccione...</option>
                    <option value="saved">Guardado</option>
                    <option value="pending">Pendiente</option>
                    <option value="approved">Aprobado</option>
                    <option value="rejected">Rechazado</option>
                </select>
            </fieldset>  

            <fieldset class="form-group col-12 col-md-2">
                <label for="for_status_purchase_search">Estado Proceso Compra</label>
                <select name="status_purchase_search" class="form-control form-control-sm" wire:model.debounce.500ms="selectedStatusPurchase">
                    <option value="">Seleccione...</option>
                    <option value="canceled">Anulado</option>
                    <option value="finalized">Finalizado</option>
                    <option value="in_process">En proceso</option>
                    <option value="purchased">Comprado</option>
                </select>
            </fieldset>
            
            <fieldset class="form-group col-12 col-md-1">
                <label for="for_id">ID</label>
                <input class="form-control form-control-sm" type="number" name="id_search" autocomplete="off" 
                    placeholder="001" wire:model.debounce.500ms="selectedId">
            </fieldset>

            <fieldset class="form-group col-12 col-md-1">
                <label for="for_folio">Folio</label>
                <input class="form-control form-control-sm" type="text" name="folio_search" autocomplete="off" 
                    placeholder="2022-17" wire:model.debounce.500ms="selectedFolio">
            </fieldset>

            <fieldset class="form-group col-12 col-md-3">
                <label for="for_name">Descripción</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off"
                    name="name_search" wire:model.debounce.500ms="selectedName">
            </fieldset>

            <fieldset class="form-group col-12 col-md-3">
                <label for="regiones">Periodo de Creación</label>
                <div class="input-group">
                    <input type="date" class="form-control form-control-sm" name="start_date_search" wire:model.debounce.500ms="selectedStartDate">
                    <input type="date" class="form-control form-control-sm" name="end_date_search" wire:model.debounce.500ms="selectedEndDate">
                </div>
            </fieldset>
        
        </div>
        
        <div class="form-row">
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_requester">Usuario Gestor</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder="NOMBRE / APELLIDOS"
                    name="requester_search" wire:model.debounce.500ms="selectedRequester">
            </fieldset>
            <fieldset class="form-group col-12 col-md-4">
                    <label for="for_requester_ou_id">U.O. Usuario Gestor</label>
                    @livewire('search-select-organizational-unit', [
                        'emit_name'            => 'searchedRequesterOu',
                        'selected_id'          => 'requester_ou_id',
                        'small_option'         => true,
                        'organizationalUnit'   => $organizationalUnit
                    ])
            </fieldset>
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_requester">Administrador Contrato</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder="NOMBRE / APELLIDOS"
                    name="admin_search" wire:model.debounce.500ms="selectedAdmin">
            </fieldset>
            <fieldset class="form-group col-12 col-md-4">
                <label for="for_requester_ou_id">U.O. Administrador Contrato</label>
                    @livewire('search-select-organizational-unit', [
                        'emit_name'          => 'searchedAdminOu',
                        'selected_id'        => 'admin_ou_id',
                        'small_option'       => true,
                        'organizationalUnit' => $organizationalUnit
                    ])
            </fieldset>
        </div>
        <div class="form-row">
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_purchaser">Comprador</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder="NOMBRE / APELLIDOS"
                    name="purchaser_search" wire:model.debounce.500ms="selectedPurchaser" @if($inbox == 'purchase') disabled @endif>
            </fieldset>
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_purchaser">Programa</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder=""
                    name="program_search" wire:model.debounce.500ms="selectedProgram">
            </fieldset>
            @if($inbox == 'purchase')
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_purchaser">N° O.C.</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder=""
                    name="purchase_order_search" wire:model.debounce.500ms="selectedPo">
            </fieldset>
            <fieldset class="form-group col-12 col-md-2">
                <label for="for_purchaser">N° Licitación.</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder=""
                    name="tender_search" wire:model.debounce.500ms="selectedTender">
            </fieldset>
            <fieldset class="form-group col-12 col-md-4">
                <label for="for_supplier">Proveedor</label>
                <input class="form-control form-control-sm" type="text" autocomplete="off" placeholder="NOMBRE"
                    name="supplier_search" wire:model.debounce.500ms="selectedSupplier">
            </fieldset>
            @endif
        </div>
    </div>

    <br>
    <!-- Todos los formularios -->
    @if($request_forms->count() > 0 && $inbox == 'all')
        <div class="row">
            <div class="col">
                <p class="font-weight-lighter">Total de Registros: <b>{{ $request_forms->total() }}</b></p>
            </div>
            <div class="col">
                <a class="btn btn-success btn-sm mb-1 float-right" wire:click="export"><i class="fas fa-file-excel"></i> Exportar formularios</a></h6>
                {{--@if($exporting && !$exportFinished)
                    <div class="d-inline" wire:poll="updateExportProgress">Exporting...please wait.</div>
                @endif

                @if($exportFinished)
                    Done. Download file <a class="stretched-link" wire:click="downloadExport">here</a>
                @endif--}}
                {{--@livewire('export', ['requests' => request()->all()])--}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover small">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Folio</th>
                        <th style="width: 7%">Fecha Creación</th>          
                        <th>Tipo / Mecanismo de Compra</th>
                        <th>Descripción</th>
                        <th>Usuario Gestor</th>
                        <th>Comprador</th>
                        <th>Items</th>
                        <th>Presupuesto</th>
                        <th>Etapas de aprobación</th>
                        <th style="width: 7%">Fecha de Aprobación Depto de Gestión de Abastecimiento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($request_forms as $requestForm)
                    <tr>
                        <th>{{ $requestForm->id }} <br>
                            @switch($requestForm->getStatus())
                                @case('Pendiente')
                                    <i class="fas fa-clock"></i>
                                @break

                                @case('Aprobado')
                                    <span style="color: green;">
                                    <i class="fas fa-check-circle" title="{{ $requestForm->getStatus() }}"></i>
                                    </span>
                                    @if($requestForm->purchasingProcess)
                                        <span class="badge badge-{{$requestForm->purchasingProcess->getColor()}}">{{$requestForm->purchasingProcess->getStatus()}}</span>
                                    @else
                                        <span class="badge badge-warning">En proceso</span>
                                    @endif
                                @break$
                                @case('Rechazado')
                                    <a href="">
                                        <span style="color: Tomato;">
                                            <i class="fas fa-times-circle" title="{{ $requestForm->getStatus() }}"></i>
                                        </span>
                                    </a>
                                @break
                            @endswitch
                        </th>
                        <td>
                            <a href="{{ route('request_forms.show', $requestForm->id) }}">{{ $requestForm->folio }}</a>
                                @if($requestForm->father)
                                <br>(<a href="{{ route('request_forms.show', $requestForm->father->id) }}">{{ $requestForm->father->folio }}</a>)
                                @endif
                        </td>
                        <td>
                            {{ $requestForm->created_at->format('d-m-Y H:i') }}<br>
                            {{ $requestForm->created_at->diffForHumans() }}
                        </td>          
                        <td>{{ ($requestForm->purchaseMechanism) ? $requestForm->purchaseMechanism->PurchaseMechanismValue : '' }}<br>
                            {{ $requestForm->SubtypeValue }}
                        </td>
                        <td>{{ $requestForm->name }}</td>
                        <td>{{ $requestForm->user ? $requestForm->user->FullName : 'Usuario eliminado' }}<br>
                            {{ $requestForm->userOrganizationalUnit ? $requestForm->userOrganizationalUnit->name : 'Usuario eliminado' }}
                        </td>
                        <td>{{ $requestForm->purchasers->first()->FullName?? 'No asignado' }}</td>
                        <td align="center">{{ $requestForm->quantityOfItems() }}</td>
                        <td class="text-right">{{$requestForm->symbol_currency}}{{ number_format($requestForm->estimated_expense,$requestForm->precision_currency,",",".") }}</td>
                        <td class="text-center">
                            @if($requestForm->eventRequestForms->count() > 0)
                                @foreach($requestForm->eventRequestForms as $sign)
                                    @if($sign->status == 'pending' || $sign->status == NULL)
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" 
                                            @if($sign->event_type != 'pre_finance_event') title="{{ $sign->signerOrganizationalUnit->name }}" @else title="Refrendación Presupuestaria" @endif>
                                            <i class="fas fa-clock fa-2x"></i>
                                        </span>
                                    @endif
                                    @if($sign->status == 'approved')
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" 
                                            @if($sign->event_type != 'pre_finance_event') title=" {{ $sign->signerOrganizationalUnit->name }}" @else title="Refrendación Presupuestaria" @endif
                                            style="color: green;">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </span>
                                    @endif
                                    @if($sign->status == 'rejected')
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                            @if($sign->event_type != 'pre_finance_event') title=" {{ $sign->signerOrganizationalUnit->name }}" @else title="Refrendación Presupuestaria" @endif
                                            style="color: Tomato;">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </span>
                                    @endif
                                    @if($sign->status == 'does_not_apply')
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                            @if($sign->event_type != 'pre_finance_event') title=" {{ $sign->signerOrganizationalUnit->name }}" @else title="Refrendación Presupuestaria" @endif>
                                            <i class="fas fa-ban fa-2x" title="{{ $sign->signerOrganizationalUnit->name }}"></i>
                                        </span>
                                    @endif
                                @endforeach
                            @else
                                <i class="fas fa-save fa-2x"></i>
                            @endif
                        </td>
                        <td>{{ $requestForm->approved_at ? $requestForm->approved_at->format('d-m-Y H:i') : 'No se ha firmado Documento' }}</td>

                        <td>
                            <a href="{{ route('request_forms.show', $requestForm->id) }}" class="btn btn-outline-secondary btn-sm" title="Mostrar"><i class="fas fa-eye"></i>
                            </a>
                            @if(Auth()->user()->hasPermissionTo('Request Forms: all'))
                            <a href="{{ route('request_forms.edit', $requestForm->id) }}" class="btn btn-outline-secondary btn-sm" title="Edición"><i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if(Auth()->user()->hasPermissionTo('Request Forms: all') and $requestForm->status == 'approved')
                            <!-- Button trigger modal -->            
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#exampleModal-{{ $requestForm->id }}" title="Reasignar comprador">
                            <i class="fas fa-redo"></i>
                            </button>
                            @include('request_form.purchase.modals.reasign_purchaser')
                            @endif
                            
                            @if($requestForm->signatures_file_id)
                            <a class="btn btn-info btn-sm" title="Ver Formulario de Requerimiento firmado" href="{{ $requestForm->signatures_file_id == 11 ? route('request_forms.show_file', $requestForm->requestFormFiles->first() ?? 0) : route('request_forms.signedRequestFormPDF', [$requestForm, 1]) }}" target="_blank" title="Certificado">
                            <i class="fas fa-file-contract"></i>
                            </a>
                            @endif

                            @if($requestForm->old_signatures_file_id)
                            <a class="btn btn-secondary btn-sm" title="Ver Formulario de Requerimiento Anterior firmado" href="{{ $requestForm->old_signatures_file_id == 11 ? route('request_forms.show_file', $requestForm->requestFormFiles->first() ?? 0) : route('request_forms.signedRequestFormPDF', [$requestForm, 0]) }}" target="_blank" title="Certificado">
                            <i class="fas fa-file-contract"></i>
                            </a>
                            @endif

                            @if(Auth()->user()->hasPermissionTo('Request Forms: all') && Str::contains($requestForm->subtype, 'tiempo') && !$requestForm->isBlocked() && $requestForm->status == 'approved')
                            <a onclick="return confirm('¿Está seguro/a de crear nuevo formulario de ejecución inmediata?') || event.stopImmediatePropagation()" data-toggle="modal" data-target="#processClosure-{{$requestForm->id}}" class="btn btn-outline-secondary btn-sm" title="Nuevo formulario de ejecución inmediata"><i class="fas fa-plus"></i>
                            </a>
                            @include('request_form.partials.modals.create_provision_period_select')
                            @endif

                            @if(Auth()->user()->hasPermissionTo('Request Forms: all') && $requestForm->purchasingProcess)
                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="">
                            <a href="{{ route('request_forms.supply.purchase', $requestForm) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-shopping-cart"></i></a>
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $request_forms->appends(request()->query())->links() }}
    @endif
    <!-- COMPRADORES -->
    @if($request_forms->count() > 0 && $inbox == 'purchase')
        <div class="row">
            <div class="col">
                <p class="font-weight-lighter">Total de Registros: <b>{{ $request_forms->total() }}</b></p>
            </div>
            <div class="col">
                <a class="btn btn-success btn-sm mb-1 float-right" wire:click="export"><i class="fas fa-file-excel"></i> Exportar formularios</a></h6>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered small">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Folio</th>
                        <th style="width: 7%">Fecha Creación</th>
                        <th>Tipo / Mecanismo de Compra</th>
                        <th>Descripción</th>
                        <th>Usuario Gestor</th>
                        <th>Items</th>
                        <th>Presupuesto</th>
                        <th>Vencimiento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($request_forms as $requestForm)
                    <tr>
                        <td>{{ $requestForm->id }} <br>
                            @if($requestForm->purchasingProcess)
                            <span class="badge badge-{{$requestForm->purchasingProcess->getColor()}}">{{$requestForm->purchasingProcess->getStatus()}}</span>
                            @else
                            <span class="badge badge-warning">En proceso</span>
                            @endif
                        </td>
                        <td>{{ $requestForm->folio }}</td>
                        <td>
                            {{ $requestForm->created_at->format('d-m-Y H:i') }}<br>
                            {{ $requestForm->created_at->diffForHumans() }}
                        </td>
                        <td>{{ ($requestForm->purchaseMechanism) ? $requestForm->purchaseMechanism->PurchaseMechanismValue : '' }}<br>
                            {{ $requestForm->SubtypeValue }}
                        </td>
                        <td>{{ $requestForm->name }}</td>
                        <td>{{ $requestForm->user ? $requestForm->user->FullName : 'Usuario eliminado' }}<br>
                            {{ $requestForm->user ? $requestForm->userOrganizationalUnit->name : 'Usuario eliminado' }}
                        </td>
                        <td>{{ $requestForm->quantityOfItems() }}</td>
                        <td class="text-right">{{$requestForm->symbol_currency}}{{ number_format($requestForm->estimated_expense,$requestForm->precision_currency,",",".") }}</td>
                        <td title="Aprobación: {{$requestForm->approved_at ? $requestForm->approved_at->format('d-m-Y H:i') : ''}}" >
                            {{ $requestForm->expireAt ? $requestForm->expireAt->format('d-m-Y H:i') : '' }}
                            <div style="font-weight: bold">{{' (' . $requestForm->daysToExpire . ' días)' }}</div>
                            {{--@if($requestForm->purchasingProcess && in_array($requestForm->purchasingProcess->status, ['purchased', 'finalized']))
                                {{$requestForm->purchasedOnTime}}
                            @endif--}}
                        </td>
                        <td>
                            <a href="{{ route('request_forms.show', $requestForm->id) }}" class="btn btn-outline-secondary btn-sm" title="Selección">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($requestForm->signatures_file_id)
                            <a class="btn btn-info btn-sm" title="Ver Formulario de Requerimiento firmado" href="{{ $requestForm->signatures_file_id == 11 ? route('request_forms.show_file', $requestForm->requestFormFiles->first() ?? 0) : route('request_forms.signedRequestFormPDF', [$requestForm, 1]) }}" target="_blank" title="Certificado">
                                <i class="fas fa-file-contract"></i>
                            </a>
                            @endif

                            @if($requestForm->old_signatures_file_id)
                            <a class="btn btn-secondary btn-sm" title="Ver Formulario de Requerimiento Anterior firmado" href="{{ $requestForm->old_signatures_file_id == 11 ? route('request_forms.show_file', $requestForm->requestFormFiles->first() ?? 0) : route('request_forms.signedRequestFormPDF', [$requestForm, 0]) }}" target="_blank" title="Certificado">
                                <i class="fas fa-file-contract"></i>
                            </a>
                            @endif
                            <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="">
                                <a href="{{ route('request_forms.supply.purchase', $requestForm) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-shopping-cart"></i></a>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $request_forms->appends(request()->query())->links() }}
    @endif
    <!--  REPORTE: FORM-ITEMS -->
    @if($request_forms->count() > 0 && $inbox == 'report: form-items')
        <div class="row">
            <div class="col">
                <p class="font-weight-lighter">Total de Formularios: <b>{{ $request_forms->total() }}</b></p>
            </div>
            <div class="col">
                <a class="btn btn-success btn-sm mb-1 float-right" wire:click="exportFormItems"><i class="fas fa-file-excel"></i> Exportar formularios</a></h6>
                {{--@if($exporting && !$exportFinished)
                    <div class="d-inline" wire:poll="updateExportProgress">Exporting...please wait.</div>
                @endif

                @if($exportFinished)
                    Done. Download file <a class="stretched-link" wire:click="downloadExport">here</a>
                @endif--}}
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped table-hover small">
                <thead>
                    <tr class="text-center">
                        <!-- FORMULARIO -->
                        <th>ID</th>
                        <th>Estado Fomulario</th>
                        <th>Folio</th>
                        <th>Depende de Folio</th>
                        <th>Fecha Creación</th>
                        <th>Tipo de Formulario</th>
                        <th>Mecanismo de Compra</th>
                        <th>Descripción</th>
                        <th>Programa</th>
                        <th>Usuario Gestor</th>
                        <th>Unidad Organizacional</th>
                        <th>Comprador</th>
                        <th>Items</th>
                        <th>Presupuesto</th>
                        <th>Estado Proceso Compra</th>
                        <th>Fecha de Aprobación Depto. Abastecimiento</th>
                        <!-- ITEMS -->
                        <th>N° Item</th>
                        <th>ID</th>
                        <th>Item Presupuestario</th>
                        <th>Artículo</th>
                        <th>UM</th>
                        <th>Especificaciones Técnicas</th>
                        <th>Cantidad</th>
                        <th>Valor U.</th>
                        <th>Impuestos</th>
                        <th>Total Item</th>	
                        <!-- PROCESO DE COMPRAS -->
                        <th>Estado compra</th>
                        <th>Tipo compra</th>
                        <th>ID Licitación</th>
                        <th>Fechas</th>
                        <th>Orden de compra</th>
                        <th>Proveedor RUT - nombre</th>
                        <th>Cotización</th>
                        <th>N° res.</th>
                        <th>Especificaciones Técnicas (COMPRADOR/PROVEEDOR)</th>
                        <th>Cantidad</th>
                        <th>Unidad de medida</th>
                        <th>Moneda</th>
                        <th>Precio neto</th>
                        <th>Total cargos</th>
                        <th>Total descuentos</th>
                        <th>Total impuesto</th>
                        <th>Monto Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($request_forms as $requestForm)
                    @if($requestForm->purchasingProcess && $requestForm->purchasingProcess->details->count() > 0)
                    @foreach($requestForm->purchasingProcess->details as $key => $detail)
                    <tr>
                        <td class="text-right" nowrap>{{ $requestForm->id }}</td>
                        <td class="text-center" nowrap>{{ $requestForm->getStatus() }}</td>
                        <td class="text-right" nowrap>{{ $requestForm->folio }}</td>
                        <td class="text-right" nowrap>
                            @if($requestForm->father)
                                {{ $requestForm->father->folio }}
                            @endif
                        </td>
                        <td class="text-center" nowrap>{{ $requestForm->created_at->format('d-m-Y H:i') }}</td>
                        <td nowrap>
                            {{ $requestForm->SubtypeValue }}
                        </td>
                        <td nowrap>
                            {{ ($requestForm->purchaseMechanism) ? $requestForm->purchaseMechanism->PurchaseMechanismValue : '' }}
                        </td>
                        <td nowrap>{{ substr($requestForm->name, 0, 100) }}</td>
                        <td nowrap>{{ $requestForm->associateProgram ? $requestForm->associateProgram->alias_finance.' '.$requestForm->associateProgram->period : $requestForm->program }}</td>
                        <td nowrap>{{ $requestForm->user->FullName }}</td>
                        <td nowrap>{{ $requestForm->userOrganizationalUnit->name }}</td>
                        <td nowrap>{{ $requestForm->purchasers->first()->FullName ?? 'No asignado' }}</td>
                        <td class="text-center">
                            @if($loop->first)
                                {{ $requestForm->itemRequestForms->count() }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if($loop->first)
                                {{ $requestForm->symbol_currency }}{{ number_format($requestForm->estimated_expense,$requestForm->precision_currency,",",".") }}
                            @endif
                        </td>
                        <td nowrap>
                            @if($loop->first)
                                @if($requestForm->purchasingProcess)
                                    {{ $requestForm->purchasingProcess->getStatus() }}
                                @else
                                    En proceso
                                @endif
                            @endif
                        </td>
                        <td nowrap>
                            @if($loop->first)
                                {{ $requestForm->approved_at ? $requestForm->approved_at->format('d-m-Y H:i:s') : '' }}
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="text-right">{{ $detail->id ?? '' }}</td>
                        <td class="text-right" nowrap>{{ $detail->budgetItem ? $detail->budgetItem->fullName() : '' }}</td>
                        <td nowrap>
                            @if($detail->product_id)
                                {{ optional($detail->product)->code }} {{ optional($detail->product)->name }}
                            @else
                                {{ $detail->article }}
                            @endif
                        </td>
                        <td class="text-center">{{ $detail->unit_of_measurement }}</td>
                        <td nowrap>{{ substr($detail->specification, 0, 100) }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">{{ str_replace(',00', '', number_format($detail->unit_value, 2,",",".")) }}</td>
                        <td class="text-center">{{ $detail->tax }}</td>
                        <td class="text-right">{{ number_format($detail->expense,$requestForm->precision_currency,",",".") }}</td>
                        <td class="text-center" nowrap>
                            {{ $detail->pivot->getStatus() }}
                        </td>
                        <td class="text-center" nowrap>{{ $detail->pivot->getPurchasingTypeName() }}</td>
                        <td nowrap>{{ $detail->pivot->tender ? $detail->pivot->tender->tender_number : '-' }}</td>
                        <td align="center" nowrap>
                            @if($detail->pivot->tender)
                            <button type="button" class="badge badge-pill badge-dark popover-item" id="detail-{{$detail->id}}" rel="popover"><i class="fas fa-info"></i></button>
                            <div class="popover-list-content" style="display:none;">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha creación <span class="badge badge-light">{{ $detail->pivot->tender->creation_date ? $detail->pivot->tender->creation_date->format('d-m-Y H:i') : '-' }}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha cierre <span class="badge badge-light">{{$detail->pivot->tender->closing_date ? $detail->pivot->tender->closing_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha inicio <span class="badge badge-light">{{$detail->pivot->tender->initial_date ? $detail->pivot->tender->initial_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha final <span class="badge badge-light">{{$detail->pivot->tender->final_date ? $detail->pivot->tender->final_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha publicación respuestas <span class="badge badge-light">{{$detail->pivot->tender->pub_answers_date ? $detail->pivot->tender->pub_answers_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha acto apertura <span class="badge badge-light">{{$detail->pivot->tender->opening_act_date ? $detail->pivot->tender->opening_act_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha publicación <span class="badge badge-light">{{$detail->pivot->tender->pub_date ? $detail->pivot->tender->pub_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha adjudicación <span class="badge badge-light">{{$detail->pivot->tender->grant_date ? $detail->pivot->tender->grant_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha estimada adjudicación <span class="badge badge-light">{{$detail->pivot->tender->estimated_grant_date ? $detail->pivot->tender->estimated_grant_date->format('d-m-Y H:i') : '-'}}</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Fecha visita terreno <span class="badge badge-light">{{$detail->pivot->tender->field_visit_date ? $detail->pivot->tender->field_visit_date->format('d-m-Y H:i') : '-'}}</span></li>
                                </ul>
                            </div>
                            @endif</td>
                        <td nowrap>{{ $detail->pivot->tender && $detail->pivot->tender->oc ? $detail->pivot->tender->oc->po_id : ($detail->pivot->immediatePurchase ? $detail->pivot->immediatePurchase->po_id : '-') }}</td>
                        <td nowrap>{{ $detail->pivot->tender && $detail->pivot->tender->supplier ? $detail->pivot->tender->supplier->run. ' - '.$detail->pivot->tender->supplier->name : $detail->pivot->supplier_run.' - '.$detail->pivot->supplier_name }}</td>
                        <td nowrap>{{ $detail->pivot->immediatePurchase ? $detail->pivot->immediatePurchase->cot_id : '-'}}</td>
                        <td>{{ $detail->pivot->directDeal ? $detail->pivot->directDeal->resol_direct_deal : '-'}}</td>
                        <td nowrap>Comprador: {{ substr($detail->specification, 0, 100) }} // proveedor: {{ substr($detail->pivot->supplier_specifications, 0, 100) }}</td>
                        <td align="right">{{ $detail->pivot->quantity }}</td>
                        <td>{{ $detail->unit_of_measurement }}</td>
                        <td>{{ $detail->pivot->tender ? $detail->pivot->tender->currency : '' }}</td>
                        <td align="right">{{$requestForm->symbol_currency}}{{ number_format($detail->pivot->unit_value,$requestForm->precision_currency,",",".") }}</td>
                        <td align="right">{{$requestForm->symbol_currency}}{{ number_format($detail->pivot->charges,$requestForm->precision_currency,",",".") }}</td>
                        <td align="right">{{$requestForm->symbol_currency}}{{ number_format($detail->pivot->discounts,$requestForm->precision_currency,",",".") }}</td>
                        <td>{{ $detail->pivot->tax ?? $detail->tax }}</td>
                        <td align="right">{{$requestForm->symbol_currency}}{{ number_format($detail->pivot->expense,$requestForm->precision_currency,",",".") }}</td>
                    </tr>
                    @endforeach
                    @else
                    @foreach($requestForm->itemRequestForms as $key => $detail)
                    <tr>
                        <td class="text-right" nowrap>{{ $requestForm->id }}</td>
                        <td class="text-center" nowrap>{{ $requestForm->getStatus() }}</td>
                        <td class="text-right" nowrap>{{ $requestForm->folio }}</td>
                        <td class="text-right" nowrap>
                            @if($requestForm->father)
                                {{ $requestForm->father->folio }}
                            @endif
                        </td>
                        <td class="text-center" nowrap>{{ $requestForm->created_at->format('d-m-Y H:i') }}</td>
                        <td nowrap>
                            {{ $requestForm->SubtypeValue }}
                        </td>
                        <td nowrap>
                            {{ ($requestForm->purchaseMechanism) ? $requestForm->purchaseMechanism->PurchaseMechanismValue : '' }}
                        </td>
                        <td nowrap>{{ substr($requestForm->name, 0, 100) }}</td>
                        <td nowrap>{{ $requestForm->associateProgram ? $requestForm->associateProgram->alias_finance.' '.$requestForm->associateProgram->period : $requestForm->program }}</td>
                        <td nowrap>{{ $requestForm->user->FullName }}</td>
                        <td nowrap>{{ $requestForm->userOrganizationalUnit->name }}</td>
                        <td nowrap>{{ $requestForm->purchasers->first()->FullName ?? 'No asignado' }}</td>
                        <td class="text-center">
                            @if($loop->first)
                                {{ $requestForm->itemRequestForms->count() }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if($loop->first)
                                {{ $requestForm->symbol_currency }}{{ number_format($requestForm->estimated_expense,$requestForm->precision_currency,",",".") }}
                            @endif
                        </td>
                        <td nowrap>
                            @if($loop->first)
                                @if($requestForm->purchasingProcess)
                                    {{ $requestForm->purchasingProcess->getStatus() }}
                                @else
                                    {{ $requestForm->getStatus() }}
                                @endif
                            @endif
                        </td>
                        <td nowrap>
                            @if($loop->first)
                                {{ $requestForm->approved_at ? $requestForm->approved_at->format('d-m-Y H:i:s') : '' }}
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="text-right">{{ $detail->id ?? '' }}</td>
                        <td class="text-right" nowrap>{{ $detail->budgetItem ? $detail->budgetItem->fullName() : '' }}</td>
                        <td nowrap>
                            @if($detail->product_id)
                                {{ optional($detail->product)->code }} {{ optional($detail->product)->name }}
                            @else
                                {{ $detail->article }}
                            @endif
                        </td>
                        <td class="text-center">{{ $detail->unit_of_measurement }}</td>
                        <td nowrap>{{ substr($detail->specification, 0, 100) }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">{{ str_replace(',00', '', number_format($detail->unit_value, 2,",",".")) }}</td>
                        <td class="text-center">{{ $detail->tax }}</td>
                        <td class="text-right">{{ number_format($detail->expense,$requestForm->precision_currency,",",".") }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
                    @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $request_forms->appends(request()->query())->links() }}
    @endif
    
    @if($request_forms->count() == 0)
        <div class="row">
            <div class="col">
                <p class="font-weight-lighter">Total de Registros: <b>{{ $request_forms->total() }}</b></p>
            </div>
        </div>

        <div class="alert alert-info" role="alert">
            <b>Estimado usuario</b>: No se encuentran solicitudes bajo los parámetros consultados.
        </div>
    @endif
    
</div>
