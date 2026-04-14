<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('manufacture'),
                TextEntry::make('model'),
                TextEntry::make('displacement')
                    ->numeric(),
                TextEntry::make('engine_code'),
                TextEntry::make('whp')
                    ->numeric(),
                TextEntry::make('color'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
