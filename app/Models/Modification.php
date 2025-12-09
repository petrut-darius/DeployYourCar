<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Car;

class Modification extends Model
{
    /** @use HasFactory<\Database\Factories\ModificationFactory> */
    use HasFactory;
    protected $fillable = [
        "user_id",
        "name",
        "description",
        "reason"
    ];


    //belongs to car
    public function owner() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function car() {
        return $this->belongsTo(Car::class);
    }
}
