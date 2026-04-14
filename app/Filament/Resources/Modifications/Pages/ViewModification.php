<?php

namespace App\Filament\Resources\Modifications\Pages;

use App\Filament\Resources\Modifications\ModificationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewModification extends ViewRecord
{
    protected static string $resource = ModificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
