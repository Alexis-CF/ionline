<?php

namespace App\Filament\Clusters\Documents\Resources\Agreements\ProgramResource\Pages;

use App\Filament\Clusters\Documents\Resources\Agreements\ProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgram extends EditRecord
{
    protected static string $resource = ProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}