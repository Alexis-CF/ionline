<?php

namespace App\Http\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Finance\Dte;
use App\Models\Sigfe\PdfBackup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class InstitutionalPayment extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $fecha;
    public $receptor;

    protected $listeners = [
        'pdfRefresh'
    ];

    public $filters = [
        'id' => null,
        'emisor' => null,
        'folio_pago' => null,
        'sin_firma' => 'Todos',
        'firmado' => 'Todos',
    ];


    public function search()
    {
        $this->resetPage();
    }


    public function pdfRefresh()
    {

        // $this->render();
        // $this->resetPage();
        // $this->dtes = $this->loadDtes()->items();
        // $this->dtes = $this->loadDtes()->paginate(5)->items();
        // dd($this->loadDtes());
        return [];
    }

    public function save($dte_id)
    {
        $dte = Dte::find($dte_id);
        if($this->fecha != null){
            $dte->update(['fecha' => $this->fecha]);
        }
        if($this->receptor != null){
            $dte->update(['receptor' => $this->receptor]);
        }
        // return [];
    }

    public function delete($dte_id, $key)
    {
        $dte = Dte::find($dte_id);
        $dte->update([$key => null]);
    }

    public function loadDtes()
    {
        $query = Dte::query()
            ->with([
                'comprobantePago',
                'requestForm.contractManager',
                'contractManager',
                'establishment:id,name',
                'tgrPayedDte:id,folio',
                'receptions:id,dte_id,status',
                'filesPdf'
            ])
            ->whereNull('rejected')
            ->where('tipo_documento', 'LIKE', 'factura_%')
            ->where('all_receptions', 1)
            ->where('establishment_id', auth()->user()->organizationalUnit->establishment_id)
            ->where('payment_ready', 1);

        // Aplica filtros
        if ($this->filters['id']) {
            $query->where('id', $this->filters['id']);
        }
        if ($this->filters['emisor']) {
            $query->where('emisor', 'LIKE', '%' . $this->filters['emisor'] . '%');
        }
        if ($this->filters['folio_pago']) {
            $query->where('paid_folio', 'LIKE', '%' . $this->filters['folio_pago'] . '%');
        }

        if ($this->filters['sin_firma'] == 'Subidos') {
            $query->whereHas('comprobantePago');
        } elseif ($this->filters['sin_firma'] == 'Sin Subir') {
            $query->whereDoesntHave('comprobantePago');
        }

        // Filtrar los resultados según el estado de todas las aprobaciones
        if ($this->filters['firmado'] == 'Firmados') {
            $query->whereHas('comprobantePago.approvals', function (Builder $query) {
                $query->where('status', 1);
            }, '=', 2);
        } elseif ($this->filters['firmado'] == 'Pendientes') {
            $query->whereHas('comprobantePago.approvals', function (Builder $query) {
                $query->where('status', '!=', 1);
            });
        }
        // return $query->get();
        return $query->paginate(5);

    }

    public function render(): View|Factory
    {
        return view('livewire.finance.institutional-payment', ['dtes' => $this->loadDtes()]);
    }
}

