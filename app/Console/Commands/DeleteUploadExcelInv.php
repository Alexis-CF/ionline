<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inv\Inventory;
use App\Models\Inv\InventoryMovement;

class DeleteUploadExcelInv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteUploadExcelInv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar inventarios y movimientos creados desde una fecha específica';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Definir la fecha directamente en el código
        $fecha = '28-12-2023';
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fecha)->startOfDay();
        $inventarios = Inventory::where('created_at', '>=', $fechaInicio)->get();

        foreach ($inventarios as $inventario) {
            InventoryMovement::where('inventory_id', $inventario->id)->delete();
        }
        $inventarios->each->delete();
        $this->info('Movimientos e inventarios creados desde el ' . $fecha . ' eliminados correctamente.');

        return Command::SUCCESS;
    }
}
