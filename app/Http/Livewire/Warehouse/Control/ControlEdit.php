<?php

namespace App\Http\Livewire\Warehouse\Control;

use App\Models\Warehouse\Store;
use Livewire\Component;

class ControlEdit extends Component
{
    public $store;
    public $control;
    public $type;
    public $date;
    public $note;
    public $guide_date;
    public $guide_number;
    public $invoice_date;
    public $invoice_number;
    public $type_dispatch_id;
    public $type_reception_id;
    public $origin_id;
    public $destination_id;
    public $store_origin_id;
    public $store_destination_id;
    public $stores;

    public $rulesReceiving = [
        'date'              => 'required|date_format:Y-m-d',
        'note'              => 'nullable|string|min:2|max:255',
        'origin_id'         => 'nullable|required_if:type_reception_id,1|integer|exists:wre_origins,id',
        'store_origin_id'   => 'nullable|required_if:type_reception_id,2|integer|exists:wre_type_receptions,id',
        'guide_date'        => 'nullable|required_if:type_reception_id,4|date_format:Y-m-d',
        'guide_number'      => 'nullable|required_if:type_reception_id,4',
        'invoice_date'      => 'nullable|required_if:type_reception_id,4|date_format:Y-m-d',
        'invoice_number'    => 'nullable|required_if:type_reception_id,4',
    ];

    public $rulesDispatch = [
        'date'                  => 'required|date_format:Y-m-d',
        'note'                  => 'nullable|string|min:2|max:255',
        'destination_id'        => 'nullable|required_if:type_dispatch_id,1|integer|exists:wre_destinations,id',
        'store_destination_id'  => 'nullable|required_if:type_dispatch_id,3|integer|exists:wre_type_receptions,id',
    ];

    public function mount()
    {
        $this->date = $this->control->date_format;
        $this->note = $this->control->note;
        $this->guide_date = $this->control->guide_date;
        $this->guide_number = $this->control->guide_number;
        $this->invoice_date = $this->control->invoice_date;
        $this->invoice_number = $this->control->invoice_number;

        $this->origin_id = $this->control->origin_id;
        $this->destination_id = $this->control->destination_id;
        $this->type_dispatch_id = $this->control->type_dispatch_id;
        $this->type_reception_id = $this->control->type_reception_id;
        $this->store_origin_id = $this->control->store_origin_id;
        $this->store_destination_id = $this->control->store_destination_id;
        $this->stores = Store::all();
    }

    public function render()
    {
        return view('livewire.warehouse.control.control-edit');
    }

    public function controlUpdate()
    {
        $rules = ($this->type == 'receiving') ? $this->rulesReceiving : $this->rulesDispatch;
        $dataValidated = $this->validate($rules);

        $this->control->update($dataValidated);

        session()->flash('success', "Se ha actualizado el  " . $this->control->type_format . " exitosamente.");

        return redirect()->route('warehouse.control.add-product', [
            'store' => $this->store,
            'control'  => $this->control
        ]);
    }
}
