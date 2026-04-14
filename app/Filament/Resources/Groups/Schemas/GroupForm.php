<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\CheckboxList;

class GroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                CheckboxList::make('permissions')
                    ->relationship('permissions', 'name')
                    ->columns(2)
                    ->gridDirection('row'),
            ]);
    }
}
