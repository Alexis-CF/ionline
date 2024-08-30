<?php

namespace App\Livewire\Allowances;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\User;

class ShowPosition extends Component
{
    public $position;

    public $searchedUser;

    protected $rules = [
        'position'  => 'required'
    ];
    
    protected $messages = [
        'position.required' => 'Favor completar este campo.'
    ];

    public function mount(){
        if($this->position) {            
            $this->setPosition($this->position);
        }
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('livewire.allowances.show-position');
    }

    #[On('searchedUser')]
    public function searchedUser($userId) {
        $this->searchedUser = User::find($userId);
        $this->position = $this->searchedUser->position;
        
        /* Se emite position a Allowance */
        $this->dispatch('emitPosition', position: $this->position);
    }

    public function updatedPosition($positionValue)
    {
        /* Se emite position a Allowance */
        $this->dispatch('emitPositionValue', position: $positionValue);
    }
}
