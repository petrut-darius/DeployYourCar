<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Modification;
use App\Models\Story;
use App\Models\Type;
use App\Models\Tag;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Car extends Model implements HasMedia
{
    protected $fillable = [
        "user_id",
        "manufacture",
        "model",
        "displacement",
        "engine_code",
        "whp",
        "color",
    ];

    public function registerMediaConversions(?Media $media = null):void {
        $this->addMediaConversion("show_page")->width(900)->height(600)->sharpen(30);
    }

    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory, InteractsWithMedia;

    public function owner() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function modifications() {
        return $this->hasMany(Modification::class);
    }

    public function story() {
        return $this->hasOne(Story::class);
    }

    public function types() {
        return $this->belongsToMany(Type::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
