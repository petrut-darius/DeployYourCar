<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;

class User extends Authenticatable
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
        if(Auth::user()->id === $this->id && Context::hasHidden("permissions")) {
            return Context::getHidden("permissions");
        }

        //ia permisiunile de la fiecare group al userului
        $groupPermissions = $this->groups()->with("permissions")->get()->pluck("permissions")->flatten()->pluck("name");
    
        $permissions = collect($this->permissions);

        return $groupPermissions->merge($permissions)->unique()->map(function($permission) {
            return strtolower($permission);
        }); 
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
