<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),

                        TextEntry::make('email')
                            ->label('Email address')
                            ->copyable(),

                        TextEntry::make('bio')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        ImageEntry::make('profile_image')
                            ->placeholder('-')
                            ->disk('public')
                            ->circular(),

                        TextEntry::make('email_verified_at')
                            ->label('Email verified at')
                            ->dateTime()
                            ->placeholder('-')
                            ->badge()
                            ->color(fn ($record) => $record->email_verified_at ? 'success' : 'danger'),

                        IconEntry::make('is_super_admin')
                            ->label('Super admin')
                            ->boolean(),

                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('Groups & Permissions')
                    ->schema([
                        TextEntry::make('groups.name')
                            ->label('Groups')
                            ->badge()
                            ->color('info')
                            ->separator(',')
                            ->placeholder('No groups assigned.'),

                        TextEntry::make('permissions.name')
                            ->label('Direct permissions')
                            ->badge()
                            ->color('warning')
                            ->separator(',')
                            ->placeholder('No direct permissions assigned.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}