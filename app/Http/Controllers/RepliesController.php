<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\Car;
use Illuminate\Support\Facades\Cache;

class RepliesController extends Controller
{
    public function storeForCar(Request $request, Car $car) {
        $data = $request->validate([
            "content"=> "required|max:255",
        ]);

        $car->replies()->create([
            "content" => $data["content"],
            "user_id" => auth()->user()->id,
        ]);

        Cache::tags(["cars"])->flush();

        return redirect()->back();
    }

    public function storeForReply(Request $request, Reply $reply) {
        $data = $request->validate([
            "content"=> "required|max:255",
        ]);

        $reply->replies()->create([
            "content" => $data["content"],
            "user_id" => auth()->user()->id,
        ]);

        Cache::tags(["cars"])->flush();

        return redirect()->back();
    }

    public function update(Request $request) {

    }

    public function destroy(Request $request) {
        $reply = Reply::findOrFail($request->reply);

        $reply->delete();

        Cache::tags(["cars"])->flush();

        return redirect()->back();
    }

    public function replies(Reply $reply) {
        return $reply->replies()->withCount("replies")->with("user")->get();
    }
}
