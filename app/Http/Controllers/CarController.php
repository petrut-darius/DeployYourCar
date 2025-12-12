<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Tag;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::orderBy("id", "desc")->with([
            'owner',
            'tags',
            'types',
            'modifications',
            'story'
        ])->paginate(10);

        return Inertia::render("Cars/Index", [
            "cars" => CarResource::collection($cars),
        ]);
    }

    public function yourCars() {
        $user = Auth::user();
        $cars = Car::orderBy("id", "desc")->where("user_id", $user->id)->with([
            "owner",
            "tags",
            "types",
            "modifications",
            "story",
        ])->paginate(10);

        return Inertia::render("Cars/yourCars", [
            "yourCars" => CarResource::collection($cars),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Cars/Create", [
            "tags" => Tag::select("id", "name")->get(),
            "types" => Type::select("id", "name")->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        //dd($request);
        $car = Car::create([
            "user_id" => Auth::id(),
            "manufacture" => $request->manufacture,
            "model" => $request->model,
            "displacement" => $request->displacement,
            "engine_code" => $request->engineCode,
            "whp" => $request->whp,
            "color" => $request->color,
        ]);

        $car->story()->create([
            "user_id" => $car->user_id,
            "body_html" => $request->story,
        ]);

        if($request->filled("modifications")) {
            foreach($request->input("modifications") as $mod) {
                $car->modifications()->create([
                    "user_id" => $car->user_id,
                    "name" => $mod["name"],
                    "description" => $mod["description"] ?? null,
                    "reason" => $mod["reason"],
                ]);
            }
        }

        $car->tags()->sync($request->input("tags", []));
        $car->types()->sync($request->input("types", []));

        $photos = $request->file('photos');

        if ($photos && !is_array($photos)) {
            $photos = [$photos];
        }

        if ($photos) {
            foreach ($photos as $photo) {
                $car->addMedia($photo)->toMediaCollection('photos', "public");
            }
        }

        return redirect()->route("cars.show", ["car" => $car]);
        //return redirect_to("cars.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $car->load(['owner', 'modifications', 'story', 'tags', 'types', "media"]);

        return Inertia::render("Cars/Show", [
            "car" => CarResource::make($car), 
        ]);

        //cross site pt car->story cu purify
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $car->load(['owner', 'modifications', 'story', 'tags', 'types', "media"]);

        return Inertia::render("Cars/Update", [
            "car" => CarResource::make($car),
            "tags" => Tag::select("id", "name")->get(),
            "types" => Type::select("id", "name")->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        //dd($request, $car);

        $car->update([
            "manufacture" => $request->manufacture,
            "model" => $request->model,
            "displacement" => $request->displacement,
            "engine_code" => $request->engineCode,
            "whp" => $request->whp,
            "color" => $request->color,
        ]);

        $car->story()->update([
            "body_html" => $request->story,
        ]);

        if($request->filled("modifications")) {
            $car->modifications()->delete();

            foreach($request->input("modifications") as $mod) {
                $car->modifications()->create([
                    "user_id" => $car->user_id,
                    "name" => $mod["name"],
                    "description" => $mod["description"] ?? null,
                    "reason" => $mod["reason"],
                ]);
            }
        }

        $car->tags()->sync($request->input("tags", []));
        $car->types()->sync($request->input("types", []));

        $photos = $request->file('photos');

        if ($photos && !is_array($photos)) {
            $photos = [$photos];
        }

        if ($photos) {
            foreach ($photos as $photo) {
                $car->addMedia($photo)->toMediaCollection('photos', "public");
            }
        }

        return redirect()->route("cars.show", ["car" => $car]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();

        return redirect()->route("cars.index");
    }

    public function destroyPhoto(Car $car, $photo_id) {
        //dd($car->id, $photo_id);
        $media = $car->media()->where("id", $photo_id)->where("collection_name", "photos")->first();
    
        if(!$media) {
            abort(404, "Photo not found");
        }

        $media->delete();

        return redirect("cars.update", ["car" => $car]);
    }

    public function filters() {
        return response()->json([
            "tags" => Tag::all(),
            "types" => Type::all()
        ]);
    }

public function search(Request $request) {
    $query = $request->input('q', '');
    
    $tagIds = $request->input('tags', []); 
    $tagIds = is_array($tagIds) ? $tagIds : explode(',', $tagIds);

    $typeIds = $request->input('types', []); 
    $typeIds = is_array($typeIds) ? $typeIds : explode(',', $typeIds);

    $cars = Car::with(['modifications', 'tags', 'types'])
        ->when($query, function ($q) use ($query) {
            $q->where(function ($q2) use ($query) {
                $q2->where('manufacture', 'like', "%{$query}%")
                   ->orWhere('model', 'like', "%{$query}%")
                   ->orWhereHas('modifications', function($q3) use ($query) {
                       $q3->where('name', 'like', "%{$query}%");
                   });
            });
        })
        ->when($tagIds, function ($q) use ($tagIds) {
            $q->whereHas('tags', function($q2) use ($tagIds) {
                $q2->whereIn('id', $tagIds);
            });
        })
        ->when($typeIds, function ($q) use ($typeIds) {
            $q->whereHas('types', function($q2) use ($typeIds) {
                $q2->whereIn('id', $typeIds);
            });
        })
        ->orderBy('id', 'desc')
        ->get();

    return response()->json($cars);
}

}
