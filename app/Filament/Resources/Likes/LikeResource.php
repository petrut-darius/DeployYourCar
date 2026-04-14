<?php

namespace App\Filament\Resources\Likes;

use App\Filament\Resources\Likes\Pages\CreateLike;
use App\Filament\Resources\Likes\Pages\EditLike;
use App\Filament\Resources\Likes\Pages\ListLikes;
use App\Filament\Resources\Likes\Pages\ViewLike;
use App\Filament\Resources\Likes\Schemas\LikeForm;
use App\Filament\Resources\Likes\Schemas\LikeInfolist;
use App\Filament\Resources\Likes\Tables\LikesTable;
use App\Models\Like;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use App\Enums\Permissions;

class LikeResource extends BaseResource
{
    protected static Permissions $managePermission = Permissions::ManageLikes;
    protected static Permissions $createPermission = Permissions::CreateLikes;
    protected static Permissions $updatePermission = Permissions::UpdateLikes;
    protected static Permissions $deletePermission = Permissions::DeleteLikes;
    protected static ?string $model = Like::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LikeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LikeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LikesTable::configure($table);
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
            'index' => ListLikes::route('/'),
            'create' => CreateLike::route('/create'),
            'view' => ViewLike::route('/{record}'),
            'edit' => EditLike::route('/{record}/edit'),
        ];
    }
}
