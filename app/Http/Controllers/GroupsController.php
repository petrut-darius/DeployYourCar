<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use Inertia\Inertia;
use App\Models\Permission;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Groups/Index", [
            "groups" => Group::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Groups/Create", [
            "permissions" => Permission::all(),
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
        $group->load("permissions");

        return Inertia::render("Groups/Edit", [
            "group" => $group,
            "permissions" => Permission::all(),
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

        return redirect()->route("groups.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route("groups.index");
    }
}
