<?php

namespace Database\Seeders;

use App\Models\Group;
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
            //cars
            ["name" => "manage:cars", "description" => "Manage cars"],
            ["name" => "create:cars", "description" => "Create cars"],
            ["name" => "update:cars", "description" => "Update cars"],
            ["name" => "delete:cars", "description" => "Delete cars"],
            //groups
            ["name" => "manage:groups", "description" => "Manage groups"],
            ["name" => "create:groups", "description" => "Create groups"],
            ["name" => "update:groups", "description" => "Update groups"],
            ["name" => "delete:groups", "description" => "Delete groups"],
            //modifications
            ["name" => "manage:modifications", "description" => "Manage modifications"],
            ["name" => "create:modifications", "description" => "Create modifications"],
            ["name" => "update:modifications", "description" => "Update modifications"],
            ["name" => "delete:modifications", "description" => "Delete modifications"],
            //permissions
            ["name" => "manage:permissions", "description" => "Manage permissions"],
            ["name" => "create:permissions", "description" => "Create permissions"],
            ["name" => "update:permissions", "description" => "Update permissions"],
            ["name" => "delete:permissions", "description" => "Delete permissions"],
            //replies
            ["name" => "manage:replies", "description" => "Manage replies"],
            ["name" => "create:replies", "description" => "Create replies"],
            ["name" => "update:replies", "description" => "Update replies"],
            ["name" => "delete:replies", "description" => "Delete replies"],
            //stories
            ["name" => "manage:stories", "description" => "Manage stories"],
            ["name" => "create:stories", "description" => "Create stories"],
            ["name" => "update:stories", "description" => "Update stories"],
            ["name" => "delete:stories", "description" => "Delete stories"],
            //tags
            ["name" => "manage:tags", "description" => "Manage tags"],
            ["name" => "create:tags", "description" => "Create tags"],
            ["name" => "update:tags", "description" => "Update tags"],
            ["name" => "delete:tags", "description" => "Delete tags"],
            //types
            ["name" => "manage:types", "description" => "Manage types"],
            ["name" => "create:types", "description" => "Create types"],
            ["name" => "update:types", "description" => "Update types"],
            ["name" => "delete:types", "description" => "Delete types"],
            //users
            ["name" => "manage:users", "description" => "Manage users"],
            ["name" => "create:users", "description" => "Create users"],
            ["name" => "update:users", "description" => "Update users"],
            ["name" => "delete:users", "description" => "Delete users"],
        ]);

        $editorGroup = Group::create(["name" => "editor"]);
        $editorPermissions = Permission::whereIn("name", [
            "update:cars",
            "update:groups",
            "update:modifications",
            "update:permissions",
            "update:replies",
            "update:stories",
            "update:tags",
            "update:types",
            "update:users",
        ])->get();

        $editorGroup->permissions()->attach($editorPermissions->pluck("id"));

        //tags
        Tag::insert([
            ["name" => "JDM"],
            ["name" => "USDM"],
            ["name" => "EDM"],
            ["name" => "KDM"],
            ["name" => "Euro"],
            ["name" => "Aussie"],
        ]);

        //types
        Type::insert([
            ["name" => "Drift"],
            ["name" => "Time Attack"],
            ["name" => "Rally"],
            ["name" => "Drag"],
            ["name" => "Auotcross"],
            ["name" => "Show Car"],
            ["name" => "Daily Driver"],
        ]);

        $adminUser = User::factory()->create([
            'name' => 'thepdi',
            'email' => 'eminoviciidarius@gmail.com',
            "password" => "30ianpdi",
            "is_super_admin" => 1,
        ]);

        $authorEditorUser = User::factory()->create([
            'name' => 'Author/Editor',
            'email' => 'ae@example.com',
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
    }
}
