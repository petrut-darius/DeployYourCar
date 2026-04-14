<?php

namespace App\Filament\Resources\Likes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LikeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('likeable_type')
                    ->required(),
                TextInput::make('likeable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
