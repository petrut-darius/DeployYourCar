<?php

namespace App\Http\Controllers;

use App\Events\CarLikedEvent;
use App\Events\ReplyLikedEvent;
use App\Notifications\CarLiked;
use App\Notifications\ReplyLiked;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Reply;
use Illuminate\Support\Facades\Cache;

class LikesController extends Controller
{
    public function storeForCar(Request $request, Car $car) {
        $car->likes()->create([
            "user_id" => auth()->user()->id,
        ]);

        Cache::tags(["cars"])->flush();

        $car->owner->notify(new CarLiked(auth()->user(), $car));

        event(new CarLikedEvent(auth()->user(), $car));

        return redirect()->back();
    }

    public function storeForReply(Request $request, Reply $reply) {
        $reply->likes()->create([
            "user_id" => auth()->user()->id,
        ]);

        $car = $reply->repliable instanceof Car ? $reply->repliable : null;

        $reply->user->notify(new ReplyLiked(auth()->user(), $car, $reply));

        event(new ReplyLikedEvent(auth()->user(), $reply));

        return redirect()->back();
    }

    public function destroyForCar(Request $request, Car $car) {
        $car->likes()->where("user_id", auth()->user()->id)->delete();

        return redirect()->back();
    }

    public function destroyForReply(Request $request, Reply $reply) {
        $reply->likes()->where("user_id", auth()->user()->id)->delete();

        return redirect()->back();
    }
}
