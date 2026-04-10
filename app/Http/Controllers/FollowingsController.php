<?php

namespace App\Http\Controllers;

use App\Events\NewFollowerEvent;
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
        $followedUser = User::findOrFail($request->user);

        //dd($user->id, $followingUser->id);

        if($user->id === $followedUser->id) {
            return Inertia::render("Users/Show", [
                "user" => $user,
            ]);
        }

        if(!$user->following()->where("following_id", $followedUser->id)->exists()) {
            $user->following()->attach($followedUser->id);

            $followedUser->notify(new NewFollower($user));

            event(new NewFollowerEvent($user, $user->id, $followedUser->id));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(!Auth::check() && !Auth::user()) {
            return redirect()->back();
        }

        $unfollowedUser = User::findOrFail($user->id);
        $user = Auth::user();

        if($user->id === $unfollowedUser->id) {
            return Inertia::render("Users/Show", [
                "user" => $user,
            ]);
        }

        if($user->following->where("following_id", $unfollowedUser->id)) {
            $user->following()->detach($unfollowedUser);
        }
    }
}
