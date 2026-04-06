<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ModificationResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TypeResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "manufacture" => $this->manufacture,
            "model" => $this->model,
            "displacement" => $this->displacement,
            "engineCode" => $this->engine_code,
            "whp" => $this->whp,
            "color" => $this->color,
            "owner" => [
                "id" => $this->owner->id,
                "name" => $this->owner->name,
            ],
            "modifications" => ModificationResource::collection($this->modifications),
            "story" => $this->story ? StoryResource::make($this->story) : null,
            "tags" => $this->tags ? TagResource::collection($this->tags) : null,
            "types" => $this->types ? TypeResource::collection($this->types) : null,
            "photos" => $this->getMedia("cars")->map(function (Media $media) {
                return [
                    "id" => $media->id,
                    "original_url" => $media->getUrl(),
                ];
            }),
            "replies" => $this->replies->map(function ($reply) {
                return [
                    "id" => $reply->id,
                    "content" => $reply->content,
                    "replies_count" => $reply->replies_count,
                    "user" => [
                        "id" => $reply->user->id,
                        "name"=> $reply->user->name,
                    ],
                    "isLiking" => auth()->check() ? $reply->likes()->where("user_id", auth()->id())->exists() : false,
                ]; 
            }),
            
        ];
    }
}
