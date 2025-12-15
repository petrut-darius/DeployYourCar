<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Permission;
use App\Models\Group;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Users/Index", [
            "users" => User::all(),
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
        return Inertia::render("Users/Show", [
            "user" => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render("Users/Edit", [
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "permission_ids" => $user->getAllPermissionIds(),
                "group_ids" => $user->groups->pluck("id"),
            ],
            "permissions" => Permission::select("id", "name")->get(),
            "groups" => Group::select("id", "name")->get()
        ]);
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

        return redirect()->route("users.index");
    }
}
