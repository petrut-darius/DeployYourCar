<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            "user" => $user,
            "permissions" => Permission::all(),
            "groups" => Group::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $permissions = Permission::whereIn("id", $request->permissions)->get();

        $user->update([
            "name" => $request->input("name"),
            "permissions" => $permissions->pluck("name"),
        ]);

        $user->groups()->sync($request->input("group", []));

        return redirect()->route("users.show");
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
