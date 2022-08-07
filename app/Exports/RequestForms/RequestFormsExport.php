<?php

namespace App\Exports\RequestForms;

use App\Models\RequestForms\RequestForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RequestFormsExport implements FromCollection, WithMapping, ShouldAutoSize, WithHeadings
{
    use Exportable;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return RequestForm::with('user', 'userOrganizationalUnit', 'purchaseMechanism', 'eventRequestForms.signerOrganizationalUnit', 'father:id,folio,has_increased_expense')
                          ->Search($this->request)->get();
    }

    public function headings(): array
    {
        return [
          'ID',
          'Estado',
          'Folio',
          'Depende de folio',
          'Fecha Creación',
          'Tipo / Mecanismo de Compra',
          'Descripción',
          'Usuario Gestor',
          'Comprador',
          'Items',
          '',
          'Presupuesto',
          'Estado Proceso compra'
        ];
    }

    public function map($requestForm): array
    {
        return [
            $requestForm->id,
            $requestForm->getStatus(),
            $requestForm->folio,
            $requestForm->father ? $requestForm->father->folio : '',
            $requestForm->created_at->format('d-m-Y H:i'),
            ($requestForm->purchaseMechanism ? $requestForm->purchaseMechanism->PurchaseMechanismValue : '').' '.$requestForm->SubtypeValue,
            $requestForm->name,
            ($requestForm->user ? $requestForm->user->FullName : 'Usuario eliminado').' '.($requestForm->userOrganizationalUnit ? $requestForm->userOrganizationalUnit->name : 'UO eliminado'),
            $requestForm->purchasers->first()->FullName ?? 'No asignado',
            $requestForm->quantityOfItems(),
            $requestForm->symbol_currency,
            number_format($requestForm->estimated_expense,$requestForm->precision_currency,".",""),
            $requestForm->getStatus() == 'Aprobado' ? ($requestForm->purchasingProcess ? $requestForm->purchasingProcess->getStatus() : 'En espera') : '',
        ];
    }
}
