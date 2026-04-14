<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use App\Models\Like;

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
        "bio",
        "profile_image",
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

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function following() {
        return $this->belongsToMany(User::class, "follow_user", "user_id", "following_id");
    }

    public function followers() {
        return $this->belongsToMany(User::class, "follow_user", "following_id", "user_id");
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

    public function isSuperAdmin() {
        return $this->is_super_admin;
    }

    public function canAccessAdmin(): bool
    {
       if ($this->isSuperAdmin()) return true;
       if (!empty($this->permissions)) return true;

       return !empty($this->getAllPermissions());
    }

    public function getAllPermissions()
    {
        return cache()->remember(
            "user_{$this->id}_permissions",
            now()->addMinutes(10),
            function () {
                $groupPermissions = $this->groups()
                    ->with("permissions:name")
                    ->get()
                    ->pluck("permissions")
                    ->flatten()
                    ->pluck("name");

                return collect($this->permissions ?? [])
                    ->merge($groupPermissions)
                    ->unique()
                    ->map(fn($p) => strtolower($p))
                    ->values();
            }
        );
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
}
