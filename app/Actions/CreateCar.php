<?php

namespace App\Actions;

use App\Models\Car;
use Illuminate\Support\Facades\DB;

class CreateCar
{
    public function execute(array $data, int $userId): Car {

        return DB::transaction(function() use ($data, $userId) {


            $car = Car::create([
                "user_id" => $userId,
                "manufacture" => $data['manufacture'],
                "model" => $data['model'],
                "displacement" => $data['displacement'],
                "engine_code" => $data['engineCode'],
                "whp" => $data['whp'],
                "color" => $data['color'],
            ]);

            $car->story()->create([
                "user_id" => $userId,
                "body_html" => $data['story'],
            ]);

            foreach($data["modifications"] ?? [] as $mod) {
                if($mod["name"] == null) {
                    continue;
                }

                $car->modifications()->create([
                    "user_id" => $userId,
                    "name" => $mod["name"],
                    "description" => $mod["description"] ?? null,
                    "reason" => $mod["reason"],
                ]);
            }

            $car->tags()->sync($data['tags'] ?? []);
            $car->types()->sync($data['types'] ?? []);

            if(!empty($data["photos"])) {
                foreach((array) $data["photos"] as $photo) {
                    $car->addMedia($photo)->toMediaCollection('photos', "public");
                }
            }

            return $car;
        });
    }
}
