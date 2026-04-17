<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\Permissions;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission([Permissions::CreateUsers->value, Permissions::ManageUsers->value]) || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if($user->isSuperAdmin()) return true;

        if(!$user->hasPermission(Permissions::UpdateUsers->value)) return false;

        if(!$user->isSuperAdmin() && $model->isSuperAdmin()) return false;

        if($model->hasPermission(Permissions::ManageUsers->value) && !$user->isSuperAdmin()) return false;
        
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if($user->isSuperAdmin()) return true;

        if(!$user->hasPermission(Permissions::DeleteUsers->value)) return false;

        if($user->id === $model->id) return false;

        if(!$user->isSuperAdmin() && $model->isSuperAdmin()) return false;

        if($model->hasPermission(Permissions::ManageUsers->value) && !$user->isSuperAdmin()) return false;
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
