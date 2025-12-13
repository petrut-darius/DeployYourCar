<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\CarPermissions;

class rPolicy
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
    public function createCar(User $user): bool
    {
        //duce la modeulu car
        return $user->hasPermission(CarPermissions::CRETE) ? Respnese::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateCar(User $user, Car $car): bool
    {
        if($user->id !== $car->owner->id) {
            return $user->hasPermissions(CarPermissions::UPDATE_ANY) ? Response::allow() : Response::denyAsNotFound();
        }

        return $user->hasPermission(CarPermissions::UPDATE) && $user->id === $car->owner->id ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteCar(User $user, Car $car): bool
    {
        if($user->id !== $car->owner->id) {
            return $user->hasPermissions(CarPermissions::DELETE_ANY) ? Response::allow() : Response::denyAsNotFound();
        }

        return $user->hasPermission(CarPermissions::DELETE) ? Response::allow() : Response::denyAsNotFound();
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
