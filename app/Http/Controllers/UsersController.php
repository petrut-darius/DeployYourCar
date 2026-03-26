<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
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
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = Cache::remember("users:show:{$user->id}", 60, function() use ($user) {
            return $user->load("groups");
        });

        return Inertia::render("Users/Show", [
            "user" => $user,
            "isFollowing" => request()->user()?->following()->where("following_id", $user->id)->exists(),
        ]);
    }
}
