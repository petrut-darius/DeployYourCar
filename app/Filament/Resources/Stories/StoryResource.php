<?php

namespace App\Filament\Resources\Stories;

use App\Filament\Resources\Stories\Pages\CreateStory;
use App\Filament\Resources\Stories\Pages\EditStory;
use App\Filament\Resources\Stories\Pages\ListStories;
use App\Filament\Resources\Stories\Pages\ViewStory;
use App\Filament\Resources\Stories\Schemas\StoryForm;
use App\Filament\Resources\Stories\Schemas\StoryInfolist;
use App\Filament\Resources\Stories\Tables\StoriesTable;
use App\Models\Story;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use App\Enums\Permissions;

class StoryResource extends BaseResource
{
    protected static Permissions $managePermission = Permissions::ManageStories;
    protected static Permissions $createPermission = Permissions::CreateStories;
    protected static Permissions $updatePermission = Permissions::UpdateStories;
    protected static Permissions $deletePermission = Permissions::DeleteStories;

    protected static ?string $model = Story::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoriesTable::configure($table);
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
            'index' => ListStories::route('/'),
            'create' => CreateStory::route('/create'),
            'view' => ViewStory::route('/{record}'),
            'edit' => EditStory::route('/{record}/edit'),
        ];
    }
}
