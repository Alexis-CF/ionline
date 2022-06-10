<?php

namespace App\Http\Livewire\Parameters\Program;

use Livewire\Component;

use App\Models\Parameters\Subtitle;

class ProgramEdit extends Component
{
    public $program;
    public $name;
    public $alias;
    public $alias_finance;
    public $financial_type;
    public $folio;
    public $subtitle_id;
    public $amount;
    public $period;
    public $start_date;
    public $end_date;
    public $description;

    public $subtitles;

    public function rules()
    {
        return [
            'name'          => 'required|string|min:2|max:255',
            'alias'         => 'required|string|min:2|max:50',
            'alias_finance' => 'nullable|string|min:2|max:150',
            'financial_type'=> 'nullable|string|min:2|max:50',
            'folio'         => 'nullable|integer|min:2|max:9999',
            'subtitle_id'   => 'required|exists:cfg_subtitles,id',
            'amount'        => 'nullable|integer|min:1|max:9999999999',
            'period'        => 'required|integer|min:2000|max:2100',
            'start_date'    => 'nullable|date_format:Y-m-d',
            'end_date'      => 'nullable|date_format:Y-m-d',
            'description'   => 'nullable|string|min:2|max:255',
        ];
    }

    public function render()
    {
        return view('livewire.parameters.program.program-edit');
    }

    public function mount()
    {
        $this->name = $this->program->name;
        $this->alias = $this->program->alias;
        $this->alias_finance = $this->program->alias_finance;
        $this->financial_type = $this->program->financial_type;
        $this->folio = $this->program->folio;
        $this->subtitle_id = $this->program->subtitle_id;
        $this->amount = $this->program->amount;
        $this->period = $this->program->period;
        $this->start_date = $this->program->start_date ? $this->program->start_date->format('Y-m-d') : null;
        $this->end_date = $this->program->end_date ? $this->program->end_date->format('Y-m-d') : null;
        $this->description = $this->program->description;

        $this->subtitles = Subtitle::pluck('name','id');
    }

    public function updateProgram()
    {
        $dataValidated = $this->validate();
        $this->program->update($dataValidated);

        session()->flash('success', 'El programa fue actualizado exitosamente.');
        return redirect()->route('parameters.programs.index');
    }
}
