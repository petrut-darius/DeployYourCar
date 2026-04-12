<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    
    protected $fillable = [
        "content",
        "user_id",
    ];

    public function repliable() {
        return $this->morphTo();
    }

    public function replies() {
        return $this->morphMany(Reply::class, "repliable");
    }

    public function likes() {
        return $this->morphMany(Like::class, "likeable");
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getRootCar() {
        $repliable = $this->repliable;

        while ($repliable instanceof Reply) {
            $repliable = $repliable->repliable;
        }

        return $repliable instanceof Car ? $repliable : null;
    }
}
