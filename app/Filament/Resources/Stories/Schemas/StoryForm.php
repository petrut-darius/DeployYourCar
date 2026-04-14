<?php

namespace App\Filament\Resources\Stories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StoryForm
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
                Textarea::make('body_text')
                    ->columnSpanFull(),
                Textarea::make('body_html')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
