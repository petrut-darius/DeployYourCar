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
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                            ->required(),

                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->hiddenOn(Operation::Edit),

                        DateTimePicker::make('email_verified_at'),

                        Textarea::make('bio')
                            ->columnSpanFull(),

                        FileUpload::make('profile_image')
                            ->image()
                            ->columnSpanFull(),
                    ]),

                Section::make('Group & Permissions')
                    ->schema([
                        Select::make('groups')
                            ->label('Groups')
                            ->relationship('groups', 'name')
                            ->multiple()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?array $state) {
                                if (empty($state)) {
                                    $set('permissions', []);
                                    return;
                                }

                                $groupPermissions = Group::whereIn('id', $state)
                                    ->with('permissions')
                                    ->get()
                                    ->flatMap(fn ($group) => $group->permissions->pluck('name'))
                                    ->unique()
                                    ->toArray();

                                $set('permissions', $groupPermissions);
                            }),

                        TextEntry::make('group_permissions_info')
                            ->label('Permissions inherited from group')
                            ->state(function (Get $get): string {
                                $groupId = $get('group_id');
                                if (!$groupId) return '—';

                                return Group::find($groupId)
                                    ?->permissions
                                    ->pluck('name')
                                    ->join(', ') ?: 'This group has no permissions.';
                            })
                            ->visible(fn (Get $get): bool => filled($get('group_id'))),

                        CheckboxList::make('permissions')
                            ->label('Individual Permissions')
                            ->options(
                                Permission::query()->pluck('name', 'name')->toArray()
                            )
                            ->columns(2)
                            ->helperText('Checked permissions are saved directly to this user regardless of group.'),
                    ]),
            ]);
    }
}
