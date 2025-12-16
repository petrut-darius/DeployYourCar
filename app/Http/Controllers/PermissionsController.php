<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Cache::tags(["permissions"])->remember("index", 60, function() {
            return Permission::all();
        });

        return Inertia::render("Permissions/Index", [
            "permissions" => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Permissions/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        Permission::create($request->all());

        Cache::tags(["permissions"])->flush();
        Cache::tags(["groups"])->flush();

        return redirect()->route("permissions.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $permission = Cache::tags(["permissions"])->remember("show:{$permission->id}", 60, function() use ($permission){
            return Permission::findOrFail($permission->id);
        });

        return Inertia::render("Permissions/Edit", [
            "permission" => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update([
            "description" => $request->input("description"),
        ]);

        Cache::tags(["permissions"])->flush();
        Cache::tags(["groups"])->flush();

        return redirect()->route("permissions.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        Cache::tags(["permissions"])->flush();
        Cache::tags(["groups"])->flush();

        return redirect()->route("permissions.index");
    }
}
