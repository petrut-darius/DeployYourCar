<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "permissions",
    ];

    //ce valori sa ia live si ii zice si tipu de data gen array/string/boolean/int/etc.
    protected $attributes = [
        "permissions" => "[]",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    //ce tip de date sa dea
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            "permissions" => "array"
        ];
    }

    public function getAllPermissions() {

        //Context = sesiune
        if(Context::hasHidden("permissions")) {
            return collect(Context::getHidden("permissions"));
        }

        //ia permisiunile de la fiecare group al userului
        $groupPermissions = $this->groups()->with("permissions:name")->get()->pluck("permissions")->flatten()->pluck("name");
    
        $permissions = collect($this->permissions ?? []);

        $allPermissions = $groupPermissions->merge($permissions)->unique()->map(fn ($permission) => strtolower($permission));

        Context::addHidden("permissions", $allPermissions);

        return $allPermissions;
    }

    public function getAllPermissionIds() {

        //Context = sesiune
        if(Auth::check() && Auth::user()->id === $this->id && Context::hasHidden("permissions")) {
            return collect(Context::getHidden("permissions"))->values();
        }

        //ia permisiunile de la fiecare group al userului
        $groupPermissionIds = $this->groups()->with("permissions:id")->get()->pluck("permissions")->flatten()->pluck("id");
    
        $permissionIds = collect($this->permissions)->map(fn($name) => Permission::where("name", $name)->value("id"))->filter()->values();

        return $groupPermissionIds->merge($permissionIds)->unique()->values();
    }

    public function hasPermission($permission) {
        if($permission instanceof \BackedEnum) {
            $permission = $permission->value;
        }

        return $this->getAllPermissions()->contains(strtolower($permission));
    }

    public function hasAnyPermission($permissions) {

        //loop peste permisiuni sa le ia valoarea
        $perms = array_map(function($value) {
            if($value instanceof \BackedEnum) {
                $value = $value->value;
            }

            return strtolower($value);
        }, $permissions);

        //verifica daca permisiunile date se afla in permisiunile din db
        return $this->getAllPermissions()->intersect($perms)->isNotEmpty();
    }

    public function groups() {
        return $this->belongsToMany(Group::class);
    }

    public function cars() {
        return $this->hasMany(Car::class);
    }

    public function modifications() {
        return $this->hasMany(Modification::class);
    }

    public function stories() {
        return $this->hasMany(Story::class);
    }

}
