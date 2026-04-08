<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
class FollowingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if(!$request->user()) {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        }

        $user = $request->user();
        $followingUser = User::findOrFail($request->user);

        //dd($user->id, $followingUser->id);

        if($user->id === $followingUser->id) {
            return Inertia::render("Users/Show", [
                "user" => $user,
            ]);
        }

        if(!$user->following()->where("following_id", $followingUser->id)->exists()) {
            $user->following()->attach($followingUser->id);

            $followingUser->notify(new NewFollower($user));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(!Auth::check() && !Auth::user()) {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        }

        $unfollowingUser = User::findOrFail($user->id);
        $user = Auth::user();

        if($user->id === $unfollowingUser->id) {
            return Inertia::render("Users/Show", [
                "user" => $user,
            ]);
        }

        if($user->following->where("following_id", $unfollowingUser->id)) {
            $user->following()->detach($unfollowingUser);
        }
    }
}
