<?php

namespace App\Filament\Resources\Replies\Pages;

use App\Filament\Resources\Replies\ReplyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewReply extends ViewRecord
{
    protected static string $resource = ReplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
