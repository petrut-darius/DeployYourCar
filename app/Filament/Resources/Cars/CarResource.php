<?php

namespace App\Filament\Resources\Cars;

use App\Filament\Resources\Cars\Pages\CreateCar;
use App\Filament\Resources\Cars\Pages\EditCar;
use App\Filament\Resources\Cars\Pages\ListCars;
use App\Filament\Resources\Cars\Pages\ViewCar;
use App\Filament\Resources\Cars\Schemas\CarForm;
use App\Filament\Resources\Cars\Schemas\CarInfolist;
use App\Filament\Resources\Cars\Tables\CarsTable;
use App\Models\Car;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\BaseResource;
use App\Enums\Permissions;

class CarResource extends BaseResource
{
    protected static Permissions $managePermission = Permissions::ManageCars;
    protected static Permissions $createPermission = Permissions::CreateCars;
    protected static Permissions $updatePermission = Permissions::UpdateCars;
    protected static Permissions $deletePermission = Permissions::DeleteCars;

    protected static ?string $model = Car::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarsTable::configure($table);
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
            'index' => ListCars::route('/'),
            'create' => CreateCar::route('/create'),
            'view' => ViewCar::route('/{record}'),
            'edit' => EditCar::route('/{record}/edit'),
        ];
    }
}
