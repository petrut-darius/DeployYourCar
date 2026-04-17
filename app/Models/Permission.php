<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
    ];

    public function group() {
        return $this->belongsToMany(Group::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
