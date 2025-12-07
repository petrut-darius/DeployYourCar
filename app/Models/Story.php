<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Car;
use Stevebauman\Purify\Facades\Purify;

class Story extends Model
{
    /** @use HasFactory<\Database\Factories\StoryFactory> */
    use HasFactory;

    protected $casts = [
        "bodyHtml" => PurifyHtmlOnGet::class,
    ];

    protected $fillable = [
        "user_id",
        "car_id",
        "body_html",
    ];

    //belongs to car
    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function car() {
        return $this->belongsTo(Car::class);
    }
}
