<?php

namespace App\Filament\Resources\Stories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('car_id')
                    ->numeric(),
                TextEntry::make('body_text')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('body_html')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
