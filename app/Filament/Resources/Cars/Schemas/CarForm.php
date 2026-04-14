<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('manufacture')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('displacement')
                    ->required()
                    ->numeric(),
                TextInput::make('engine_code')
                    ->required(),
                TextInput::make('whp')
                    ->required()
                    ->numeric(),
                TextInput::make('color')
                    ->required(),
            ]);
    }
}
