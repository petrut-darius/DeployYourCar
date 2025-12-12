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
use Laravel\Scout\Searchable;

class Car extends Model implements HasMedia
{
    use Searchable;

    protected $fillable = [
        "user_id",
        "manufacture",
        "model",
        "displacement",
        "engine_code",
        "whp",
        "color",
    ];

    public function toSearchableArray(): array {
        return [
            "manufacture" => $this->manufacture,
            "model" => $this->model,
            "modifications" => $this->modifications->pluck( "name")->toArray(),
            "tags" => $this->tags->pluck( "name")->toArray(),
            "types" => $this->types->pluck( "name")->toArray(),
        ];
    }

    public function searchableAs(): string {
        return "cars_index";
    }

    public function registerMediaConversions(?Media $media = null):void {
        $this->addMediaConversion("show_page")->width(600);
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
