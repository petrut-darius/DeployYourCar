<?php

namespace App\Http\Controllers;

use App\Events\NewFollowerEvent;
use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InertiaToast\Facades\Toast;

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
        if(!Auth::check() && !Auth::user()) {
            Toast::error("You must be logged in to follow someone!");    
        
            return redirect()->back();
        }

        $user = Auth::user();
        $followedUser = User::findOrFail($request->user);

        if($user->id === $followedUser->id) {
            Toast::error("You can't follow yourself!");

            return redirect()->back();
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
            Toast::error("Youu can't unfollow yourself!");

            return redirect()->back();
        }

        if($user->following->where("following_id", $unfollowedUser->id)) {
            $user->following()->detach($unfollowedUser);
        }
    }
}
