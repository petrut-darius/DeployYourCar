<?php

namespace App\Filament\Resources\Replies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('repliable_type')
                    ->required(),
                TextInput::make('repliable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
