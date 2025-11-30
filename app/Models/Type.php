<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Car;

class Type extends Model
{
    //doar super adminu sa poata creea
    public function cars() {
        return $this->belongsToMany(Car::class);
    }
}
