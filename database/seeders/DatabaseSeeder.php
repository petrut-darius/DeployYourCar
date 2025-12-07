<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Story;
use App\Models\Modification;
use App\Models\Tag;
use App\Models\Type;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //to do create basic tags and types
        $user = User::factory()->create([
            'name' => 'Petrut Darius',
            'email' => 'eminoviciidarius@gmail.com',
            "password" => "30ianpdi"
        ]);

        $users = User::factory(20)->create();
        $users->push($user);

        $users->each(function ($user) {
            $cars = Car::factory(rand(1, 3))->create(['user_id' => $user->id]);
        
            $cars->each(function ($car) {
                Modification::factory()->create([
                    "car_id" => $car->id,
                ]);
            });
        });

        Story::factory(40)->make()->each(function ($story) use ($users) {
            $user = $users->random();
            $car = $user->cars()->inRandomOrder()->first();

            $story->user_id = $user->id;
            $story->car_id = $car->id;

            $story->save();
        });

        Tag::create([
            "name" => "test_tag",
        ]);

        Type::create([
            "name" => "test_type",
        ]);
    }
}
