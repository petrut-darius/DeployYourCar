<?php

namespace App\Filament\Resources\Modifications;

use App\Filament\Resources\Modifications\Pages\CreateModification;
use App\Filament\Resources\Modifications\Pages\EditModification;
use App\Filament\Resources\Modifications\Pages\ListModifications;
use App\Filament\Resources\Modifications\Pages\ViewModification;
use App\Filament\Resources\Modifications\Schemas\ModificationForm;
use App\Filament\Resources\Modifications\Schemas\ModificationInfolist;
use App\Filament\Resources\Modifications\Tables\ModificationsTable;
use App\Models\Modification;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use App\Enums\Permissions;

class ModificationResource extends BaseResource
{
    protected static Permissions $managePermission = Permissions::ManageModifications;
    protected static Permissions $createPermission = Permissions::CreateModifications;
    protected static Permissions $updatePermission = Permissions::UpdateModifications;
    protected static Permissions $deletePermission = Permissions::DeleteModifications;
    protected static ?string $model = Modification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ModificationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ModificationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModificationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModifications::route('/'),
            'create' => CreateModification::route('/create'),
            'view' => ViewModification::route('/{record}'),
            'edit' => EditModification::route('/{record}/edit'),
        ];
    }
}
