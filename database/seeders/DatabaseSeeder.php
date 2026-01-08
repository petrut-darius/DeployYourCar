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
use App\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::insert([
            ["name" => "car:create", "description" => "Create car"],
            ["name" => "car:update", "description" => "Update car"],
            ["name" => "car:update-any", "description" => "Update any car"],
            ["name" => "car:delete", "description" => "Delete car"],
            ["name" => "car:delete-any", "description" => "Delete any car"],
            ["name" => "user:create", "description" => "Create user"],
            ["name" => "permission:create", "descritpion" => "Create permission"],
        ]);

        $adminUser = User::factory()->create([
            'name' => 'thepdi',
            'email' => 'eminoviciidarius@gmail.com',
            "password" => "30ianpdi",
            "permissions" => [
                "car:create",
                "car:update-any",
                "car:delete-any",
                "user:create",
                "permission:create",
            ]
        ]);

        $authorEditorUser = User::factory()->create([
            'name' => 'Author/Editor',
            'email' => 'ae@example.com',
            "permissions" => [
                "car:create",
                "car:update-any",
                "car:delete-any",
            ]
        ]);

        $users = User::factory(20)->create();
        $users->push($adminUser);
        $users->push($authorEditorUser);

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

        //tags
        Tag::create([
            "name" => "JDM",
        ]);

        Tag::create([
            "name" => "USDM",
        ]);

        Tag::create([
            "name" => "EDM",
        ]);

        Tag::create([
            "name" => "KDM",
        ]);

        Tag::create([
            "name" => "Euro",
        ]);

        Tag::create([
            "name" => "Aussie",
        ]);

        //types
        Type::create([
            "name" => "Drift",
        ]);

        Type::create([
            "name" => "Time Attack",
        ]);

        Type::create([
            "name" => "Rally",
        ]);

        Type::create([
            "name" => "Drag",
        ]);

        Type::create([
            "name" => "Auotcross",
        ]);

        Type::create([
            "name" => "Show Car",
        ]);

        Type::create([
            "name" => "Daily Driver",
        ]);
    }
}
