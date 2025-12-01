<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ModificatPionResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TypeResource;

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
            "engine_code" => $this->engine_code,
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
        ];
    }
}
