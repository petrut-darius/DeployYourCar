<?php

namespace App\Filament\Resources\Types;

use App\Filament\Resources\Types\Pages\CreateType;
use App\Filament\Resources\Types\Pages\EditType;
use App\Filament\Resources\Types\Pages\ListTypes;
use App\Filament\Resources\Types\Pages\ViewType;
use App\Filament\Resources\Types\Schemas\TypeForm;
use App\Filament\Resources\Types\Schemas\TypeInfolist;
use App\Filament\Resources\Types\Tables\TypesTable;
use App\Models\Type;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use App\Enums\Permissions;

class TypeResource extends BaseResource
{
    protected static Permissions $managePermission = Permissions::ManageTypes;
    protected static Permissions $createPermission = Permissions::CreateTypes;
    protected static Permissions $updatePermission = Permissions::UpdateTypes;
    protected static Permissions $deletePermission = Permissions::DeleteTypes;    
    protected static ?string $model = Type::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypesTable::configure($table);
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
            'index' => ListTypes::route('/'),
            'create' => CreateType::route('/create'),
            'view' => ViewType::route('/{record}'),
            'edit' => EditType::route('/{record}/edit'),
        ];
    }
}
