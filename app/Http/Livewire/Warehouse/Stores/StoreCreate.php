<?php

namespace App\Http\Livewire\Warehouse\Stores;

use App\Models\Commune;
use App\Models\Warehouse\Store;
use Livewire\Component;

class StoreCreate extends Component
{
    public $name;
    public $address;
    public $commune_id;
    public $communes;

    public $rules = [
        'name'          => 'required|string|min:2|max:255',
        'address'       => 'required|string|min:5|max:255',
        'commune_id'    => 'required|exists:communes,id'
    ];

    public function mount()
    {
        $this->communes = Commune::all();
    }

    public function render()
    {
        return view('livewire.warehouse.stores.store-create');
    }

    public function createStore()
    {
        $dataValidated = $this->validate();
        Store::create($dataValidated);

        return redirect()->route('warehouse.stores.index');
    }
}
