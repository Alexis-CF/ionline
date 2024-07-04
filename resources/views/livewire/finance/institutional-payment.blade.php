<div>
    {{-- Do your work, then step back. --}}
    @include('finance.payments.partials.nav')

    <h3>Pagos Institucionales</h3>

    <form wire:submit.prevent="search">
        <div class="row g-2 mb-3">
            <div class="col-md-1">
                <label for="for-id" class="form-label">ID</label>
                <input type="text" class="form-control" wire:model.defer="filters.id" placeholder="id">
            </div>
            <div class="col-md-2">
                <label for="for-emisor" class="form-label">Rut</label>
                <input type="text" class="form-control" wire:model.defer="filters.emisor" placeholder="rut emisor">
            </div>
            <div class="col-md-1">
                <label for="for-folio" class="form-label">Folio DTE</label>
                <input type="text" class="form-control" wire:model.defer="filters.folio" placeholder="folio">
            </div>
            <div class="col-md-2">
                <label for="for-folio" class="form-label">Folio Comp</label>
                <input type="text" class="form-control" wire:model.defer="filters.folio_compromiso" placeholder="compromiso">
            </div>
            <div class="col-md-2">
                <label for="for-folio" class="form-label">Folio Dev</label>
                <input type="text" class="form-control" wire:model.defer="filters.folio_devengo"  placeholder="devengo">
            </div>
            <div class="col-md-2">
                <label for="for-folio" class="form-label">Folio Pago</label>
                <input type="text" class="form-control" wire:model.defer="filters.folio_pago" placeholder="folio pago">
            </div>

            <!-- Agrega más campos según tus necesidades -->
            <div class="col-md-1">
                <label for="search" class="form-label">&nbsp;</label>
                <button class="btn btn-outline-secondary form-control" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>


    <table class="table table-sm table-bordered" wire:loading.class="text-muted">

        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Archivos Asociados</th>
                <th scope="col">Beneficiario del pago</th>
                <th scope="col">Adjuntos</th>
                <th scope="col">SIGFE</th>
                <th scope="col">Fecha de Pago</th>
                <th scope="col">SIGFE</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($dtes as $dte)
                <tr>
                    <td class="small text-center">
                        {{ $dte->id }}
                    </td>
                    <td class="small">
                        <small>Documento</small>
                        <div wire:ignore>
                            @livewire('finance.upload-pdf', ['dteId' => $dte->id, 'type' => 'documento'], key('document-upload-pdf-' . rand() * $dte->id))
                        </div>
                        <hr>
                        <small>Resolucion</small>
                        <div wire:ignore>
                            @livewire('finance.upload-pdf', ['dteId' => $dte->id, 'type' => 'resolucion'], key('resolucion-upload-pdf-' . rand() * $dte->id))
                        </div>
                        <hr>
                        <small>Comprobante Bancario</small>
                        <div wire:ignore>
                            @livewire('finance.upload-pdf', ['dteId' => $dte->id, 'type' => 'comprobante_bancario'], key('bank-upload-pdf-' . rand() * $dte->id))
                        </div>
                    </td>
                    <td class="small">
                        <div wire:key="receptor_{{$dte->id}}">
                            @if ($dte->receptor)
                                {{$dte->receptor}}
                                <button type="button" class="btn btn-primary btn-sm mt-2" wire:loading.attr="disabled" wire:click="delete({{$dte->id}}, 'receptor')"><i class="fa fa-trash"></i></button>
                            @else
                            <form class="form-inline" wire:submit.prevent="save({{$dte->id}})">
                                <div class="input-group">
                                    <input type="text" class="form-control fs-6" wire:model.defer="receptor" wire:loading.attr="disabled">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" wire:loading.attr="disabled"><i class="fas fa-save"></i></button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </td>
                    <td class="small">
                        <div class="container">
                            @foreach($dte->filesPdf as $file)
                                <div wire:ignore>
                                    @livewire('finance.list-pdf', ['dteId' => $dte->id, 'file'=> $file], key('list-pdf-' . rand() * $file->id))
                                </div>
                            @endforeach
                        </div>
                        <div wire:ignore>
                            @livewire('finance.upload-pdf', ['dteId' => $dte->id, 'type' => 'adjunto', 'small' => 'true'], key('attachment-upload-pdf-' . rand() * $dte->id))
                        </div>

                    </td>
                    <td class="small">
                    <div wire:ignore>
                        <!-- SIGFE Compromiso y Devengo -->
                        <small>Compromiso</small>
                        @livewire('finance.sigfe-folio-compromiso',
                        [
                            'dte' => $dte,
                            'onlyRead' => 'true'
                        ],
                        key($dte->id))
                        @livewire('finance.sigfe-archivo-compromiso',
                        [
                            'dte' => $dte,
                            'onlyRead' => 'true'
                            ], key($dte->id))
                        <hr>
                        <small>Devengo</small>
                        @livewire('finance.sigfe-folio-devengo', [
                            'dte' => $dte,
                            'onlyRead' => 'true'
                        ], key($dte->id))
                        <hr>
                        @livewire('finance.sigfe-archivo-devengo',
                        [
                            'dte' => $dte,
                            'onlyRead' => 'true'
                        ], key($dte->id))
                    </td>
                    </div>
                    <td class="small">
                        <div wire:key="fecha_{{$dte->id}}">
                            @if ($dte->fecha)
                                {{$dte->fecha}}
                                <button type="button" class="btn btn-primary btn-sm mt-2" wire:loading.attr="disabled" wire:click="delete({{$dte->id}}, 'fecha')"><i class="fa fa-trash"></i></button>
                            @else
                                <form class="form-inline" wire:submit.prevent="save({{$dte->id}})">
                                    <div class="input-group">
                                        <input class="form-control fs-6" type="date" wire:model.defer="fecha">
                                        <button type="submit" class="btn btn-outline-primary btn-sm" wire:loading.attr="disabled"><i class="fas fa-save"></i></button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </td>
                    <td class="small">
                        <small>Pago</small>
                        <div wire:ignore>
                            @livewire('finance.upload-pdf', ['dteId' => $dte->id, 'type' => 'pago'], key('pago-upload-pdf-' . rand() * $dte->id))
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    <div wire:loading.remove>
        {{ $dtes->links() }}
    </div>


</div>
