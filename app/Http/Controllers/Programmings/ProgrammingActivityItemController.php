<?php

namespace App\Http\Controllers\Programmings;

use App\Http\Controllers\Controller;
use App\Programmings\Programming;
use App\Programmings\ProgrammingActivityItem;
use Illuminate\Http\Request;

class ProgrammingActivityItemController extends Controller
{
    public function store(Request $request)
    {
        $programming = Programming::find($request->programming_id);
        $programming->pendingItems()->attach($request->pendingItemSelectedId, ['requested_by' => auth()->id(), 'observation' => $request->observation]);
        session()->flash('info', 'Se agrega actividad pendiente satisfactoriamente.');
        return redirect()->back();
    }

    public function update(Request $request, ProgrammingActivityItem $pendingitem)
    {
        $pendingitem->update(['observation' => $request->observation]);
        session()->flash('info', 'Se actualizó la observación de la actividad pendiente satisfactoriamente.');
        return redirect()->back();
    }

    public function destroy(ProgrammingActivityItem $pendingitem)
    {
        $pendingitem->delete();
        session()->flash('info', 'Se elimina actividad pendiente satisfactoriamente.');
        return redirect()->back();
    }
}
