<?php

namespace App\Actions;

use App\Models\Car;
use Illuminate\Support\Facades\DB;

class UpdateCar
{
    public function execute(array $data, int $carId) {
        return DB::transaction(function() use ($carId, $data) {
            $car = Car::findOrFail($carId);

            $car->update([
                "manufacture" => $data['manufacture'],
                "model" => $data['model'],
                "displacement" => $data['displacement'],
                "engine_code" => $data['engineCode'],
                "whp" => $data['whp'],
                "color" => $data['color'],
            ]);

            $car->story()->update([
                "body_html" => $data['story'],
            ]);


            //create or update modifications
            foreach($data['modifications'] ?? [] as $mod) {
                if($mod["name"] == null || $mod["reason"] == null) {
                    continue;
                }

                if(!empty($mod["id"])) {
                    $car->modifications()->update([
                        "name" => $mod["name"],
                        "description" => $mod["description"] ?? null,
                        "reason" => $mod["reason"],
                    ]);
                }else{
                    $car->modifications()->create([
                        "user_id" => $car->user_id,
                        "name" => $mod["name"],
                        "description" => $mod["description"],
                        "reason" => $mod["reason"],
                    ]);
                }
            };

            //delete modifications, that where deleted when updating
            $submittedIds = collect($data["modifications"] ?? [])->pluck("id")->filter()->toArray();
            $car->modifications()->whereNotIn("id", $submittedIds)->delete();



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
