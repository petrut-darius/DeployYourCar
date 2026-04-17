<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'bio',
        'profile_image',
        'is_super_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_super_admin'    => 'boolean',
        ];
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follow_user', 'user_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follow_user', 'following_id', 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class);
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    //admin
    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    public function canAccessAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->getAllPermissions()->isNotEmpty();
    }

    public function getAllPermissions()
    {
        return cache()->remember(
            "user_{$this->id}_permissions",
            now()->addMinutes(10),
            function () {
                $groupPermissions = $this->groups()
                    ->with('permissions:id,name')
                    ->get()
                    ->pluck('permissions')//pluck takes from a collection only the value of the key you type
                    ->flatten()//from a [[a], [b]] to [a, b]
                    ->pluck('name');

                $directPermissions = $this->permissions()->pluck('name');

                return $groupPermissions
                    ->merge($directPermissions)
                    ->map(fn ($p) => strtolower($p))
                    ->unique()
                    ->values();
            }
        );
    }

    public function hasPermission(string|\BackedEnum $permission): bool
    {
        if ($permission instanceof \BackedEnum) {
            $permission = $permission->value;
        }

        return $this->getAllPermissions()->contains(strtolower($permission));
    }

    public function hasAnyPermission(array $permissions): bool
    {
        $normalized = array_map(
            fn ($p) => strtolower($p instanceof \BackedEnum ? $p->value : $p),
            $permissions
        );

        return $this->getAllPermissions()->intersect($normalized)->isNotEmpty();
    }
}