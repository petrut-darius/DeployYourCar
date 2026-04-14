<?php

namespace App\Filament\Resources\Tags;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Filament\Resources\Tags\Pages\ViewTag;
use App\Filament\Resources\Tags\Schemas\TagForm;
use App\Filament\Resources\Tags\Schemas\TagInfolist;
use App\Filament\Resources\Tags\Tables\TagsTable;
use App\Models\Tag;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use App\Enums\Permissions;

class TagResource extends BaseResource
{
    protected static Permissions $managePermission = Permissions::ManageTags;
    protected static Permissions $createPermission = Permissions::CreateTags;
    protected static Permissions $updatePermission = Permissions::UpdateTags;
    protected static Permissions $deletePermission = Permissions::DeleteTags;
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TagInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
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
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'view' => ViewTag::route('/{record}'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
