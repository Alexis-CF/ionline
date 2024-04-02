<?php

namespace App\Http\Livewire\Inventory;

use App\Models\Inv\Inventory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AssignedProducts extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $product_type;

    public function mount()
    {
        $this->product_type = '';
    }

    public function render()
    {
        return view('livewire.inventory.assigned-products', [
            'inventories' => $this->getInventories()
        ]);
    }

    public function getInventories()
    {
        $search = "%$this->search%";

        $inventories = Inventory::query()
            ->when($this->product_type == 'using', function($query) {
                $query->whereUserUsingId(Auth::id());
            })
            ->when($this->product_type == 'responsible', function ($query) {
                $query->whereUserResponsibleId(Auth::id());
            })
            ->when($this->product_type == '', function($query) {
                $query->where(function($query) {
                    $query->where('user_responsible_id', Auth::id())
                      ->orWhere('user_using_id', Auth::id());
                });
            })
            ->whereHas('lastMovement', function($query) {
                $query->whereNotNull('reception_date');
            })
            ->where(function ($query) use ($search) {
                $query->where('number', 'like', $search)->orWhere('old_number', 'like', $search) 
                      ->orWhereHas('unspscProduct', function ($query) use ($search) {
                          $query->where('name', 'like', $search);
                      })
                      ->orWhereHas('product', function ($query) use ($search) {
                          $query->where('name', 'like', $search)
                                ->orWhere('name', 'like', $search);
                      })
                      ->orWhere('description', 'like', $search)
                      ->orWhereHas('place', function ($query) use ($search) {
                        
                          $query->where('name', 'like', $search)
                          ->orWhere('architectural_design_code', 'like', $search)
                                ->orWhereHas('location', function ($query) use ($search) {
                                    $query->where('name', 'like', $search);
                                });
                      });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return $inventories;
    }
}
