<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Group;
use App\Models\Permission;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->hiddenOn(Operation::Edit),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email verified at')
                            ->native(false),

                        Textarea::make('bio')
                            ->rows(3)
                            ->columnSpanFull(),

                        FileUpload::make('profile_image')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull()
                            ->disk('public')
                            ->directory('profile-images')
                            ->visibility('public'),
                    ]),

                Section::make('Groups & Permissions')
                    ->description('Assign groups and individual permissions. Group permissions are inherited automatically.')
                    ->schema([
                        Select::make('groups')
                            ->relationship('groups', 'name')
                            ->multiple()
                            ->preload()
                            ->live()
                            ->searchable(),

                        Placeholder::make('inherited_permissions')
                            ->label('Permissions inherited from groups')
                            ->content(function (Get $get): string {
                                $groupIds = $get('groups');

                                if (empty($groupIds)) {
                                    return '—';
                                }

                                $names = Group::whereIn('id', $groupIds)
                                    ->with('permissions:id,name')
                                    ->get()
                                    ->flatMap(fn (Group $group) => $group->permissions->pluck('name'))
                                    ->unique()
                                    ->sort()
                                    ->values();

                                return $names->isNotEmpty()
                                    ? $names->join(', ')
                                    : 'No permissions assigned to selected groups.';
                            })
                            ->visible(fn (Get $get): bool => filled($get('groups'))),

                        CheckboxList::make('permissions')
                            ->label('Direct permissions')
                            ->relationship('permissions', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Permission $record) => $record->name)
                            ->columns(3)
                            ->searchable()
                            ->bulkToggleable()
                            ->helperText('These are applied directly to the user, independent of any group.'),
                    ]),
            ]);
    }
}