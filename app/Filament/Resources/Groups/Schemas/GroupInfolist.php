<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GroupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Basic Information")->columns(2)->schema([
                    TextEntry::make('name'),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->placeholder('-'),
                ]),
                Section::make("Permissions")->schema([
                    TextEntry::make("permissions.name")
                        ->label("Permissions")
                        ->badge()
                        ->color("warning")
                        ->separator(",")
                        ->placeholder("No direct permissions assigned.")
                        ->columnSpanFull(),
                ]),
            ]);
    }
}
