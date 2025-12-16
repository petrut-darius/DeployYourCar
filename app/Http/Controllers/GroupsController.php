<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Support\Facades\Cache;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Cache::tags(["groups"])->remember("index", 60, function() {
            return Group::all();
        });

        return Inertia::render("Groups/Index", [
            "groups" => $groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Cache::tags(["permissions"])->remember("index", 60, function() {
            return Permission::all();
        });

        return Inertia::render("Groups/Create", [
            "permissions" => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        $group = Group::create([
            "name" => $request->input("name"),
        ]);

        $group->permissions()->sync($request->input("permissions"));
        
        Cache::tags(["groups"])->flush();

        return redirect()->route("groups.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        $group = Cache::tags(["groups"])->remember("edit:{$group->id}", 60, function() use ($group) {
            return $group->load("permissions");
        });

        $permissions = Cache::tags(["permissios"])->remember("index", 60, function() {
            return Permission::all();
        });

        return Inertia::render("Groups/Edit", [
            "group" => $group,
            "permissions" => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update([
            "name" => $request->input("name"),
        ]);

        $group->permissions()->sync($request->input("permissions", []));

        Cache::tags(["groups"])->flush();

        return redirect()->route("groups.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();

        Cache::tags(["groups"])->flush();

        return redirect()->route("groups.index");
    }
}
