<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\CarPermissions;
use App\Enums\Permissions;

class CarPolicy
{
    /**
     * Determine whether the user can view any models.
     
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([]);
    }
    */

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Car $car): bool
    {
        //toata lumea tre sa vada
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
            return ($user->hasAnyPermission([Permissions::ManageCars->value, Permissions::CreateCars->value]) || $user->isSuperAdmin() ) ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Car $car): Response
    {
        if($user->isSuperAdmin()) return Response::allow();

        if($user->hasAnyPermission([Permissions::ManageCars->value, Permissions::UpdateCars->value])) return Response::allow();
    
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Car $car): Response
    {
        if($user->isSuperAdmin()) return Response::allow();

        if($user->hasAnyPermission([Permissions::ManageCars->value, Permissions::DeleteCars->value])) return Response::allow();

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Car $car): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Car $car): bool
    {
        return false;
    }
}
