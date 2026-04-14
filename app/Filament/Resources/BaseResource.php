<?php
namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Enums\Permissions;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource extends Resource {

    protected static Permissions $managePermission;
    protected static Permissions $createPermission;
    protected static Permissions $updatePermission;
    protected static Permissions $deletePermission;

    public static function canViewAny(): bool {
        $user = auth()->user();

        if($user->isSuperAdmin()) return true;

        return $user->hasAnyPermission([static::$createPermission, static::$updatePermission, static::$deletePermission, static::$managePermission]);
    }

    public static function canCreate(): bool {
        $user = auth()->user();

        if($user->isSuperAdmin()) return true;

        return $user->hasAnyPermission([static::$createPermission, static::$managePermission]);
    }

    public static function canUpdate(Model $record): bool {
        $user = auth()->user();

        if($user->isSuperAdmin()) return true;

        return $user->hasAnyPermission([static::$updatePermission, static::$managePermission]);
    }

    public static function canDelete(Model $record): bool {
        $user = auth()->user();

        if($user->isSuperAdmin()) return true;

        return $user->hasAnyPermission([static::$deletePermission, static::$managePermission]);
    }
}