<?php

namespace App\Filament\Resources\Modifications\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ModificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('car_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('reason')
                    ->required(),
            ]);
    }
}
