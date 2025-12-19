<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Permission;
use App\Models\Group;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Cache::remember("users:index", 60, function () {
            info("fetching users from db");
            return User::all();
        });

        return Inertia::render("Users/Index", [
            "users" => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = Cache::remember("users:show:{$user->id}", 60, function() use ($user) {
            return $user->load("groups");
        });

        return Inertia::render("Users/Show", [
            "user" => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $data = Cache::remember("users:edit:{$user->id}", 60, function() use ($user) {
            return [
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "permission_ids" => $user->getAllPermissionIds(),
                    "group_ids" => $user->groups->pluck("id"),
                ],
                "permissions" => Permission::select("id", "name")->get(),
                "groups" => Group::select("id", "name")->get()
            ];
        });

        return Inertia::render("Users/Edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $permissionNames = Permission::whereIn("id", $request->permissions ?? [])->pluck("name")->toArray();

        $user->update([
            "name" => $request->name,
            "permissions" => $permissionNames,
        ]);

        $user->groups()->sync($request->groups ?? []);

        Cache::forget("users:index");
        Cache::forget("users:show:{$user->id}");
        Cache::forget("users:edit:{$user->id}");

        return redirect()->route("users.show", $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(Auth::user()->id === $user->id) {
            abort(403);
        }

        $user->delete();

        Cache::forget("users:index");
        Cache::forget("users:show:{$user->id}");
        Cache::forget("users:edit:{$user->id}");

        return redirect()->route("users.index");
    }
}
