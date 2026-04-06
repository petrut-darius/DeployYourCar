<?php

namespace App\Http\Controllers;

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

        return redirect()->back();
    }

    public function storeForReply(Request $request, Reply $reply) {
        $reply->likes()->create([
            "user_id" => auth()->user()->id,
        ]);

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
