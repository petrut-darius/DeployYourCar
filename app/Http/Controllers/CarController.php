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
use App\CarAbilities;
use Illuminate\Support\Facades\Cache;
use Meilisearch\Scout\SearchableMeilisearchEngine;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = $request->string('q')->toString();
        $tags = array_map('intval', $request->input('tags', []));
        $types = array_map('intval', $request->input('types', []));

        if ($query || !empty($tags) || !empty($types)) {
            $cars = Car::search($query)
                ->tap(function ($meiliBuilder) use ($tags, $types) {
                    $filters = [];

                    if (!empty($tags)) {
                        $filters[] = 'tags_ids IN [' . implode(',', $tags) . ']';
                    }

                    if (!empty($types)) {
                        $filters[] = 'types_ids IN [' . implode(',', $types) . ']';
                    }

                    if (!empty($filters)) {
                        // Use raw Meilisearch filters
                        $meiliBuilder->raw(['filter' => implode(' AND ', $filters)]);
                    }
                })
                ->paginate(10);
        } else {
            $page = request('page', 1);

            $cars = Cache::tags(['cars'])->remember("index:page:{$page}", 60, function () {
                return Car::orderBy('id', 'desc')->with([
                    'owner',
                    'tags',
                    'types',
                    'modifications',
                    'story'
                ])->paginate(10);
            });
        }

        return Inertia::render('Cars/Index', [
            'cars' => CarResource::collection($cars),
            'types' => Type::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get(),
            'filters' => $request->only(['q', 'types', 'tags']),
        ]);
    }




    public function yourCars() {
        $page = request("page", 1);

        $user = Auth::user();
        $cars = Cache::tags(["cars"])->remember("user:{$user->id}:page:{$page}", 60, function() use ($user){
            return Car::where("user_id", $user->id)->with([
                'owner',
                'tags',
                'types',
                'modifications',
                'story'
            ])->paginate(10);
        });

        return Inertia::render("Cars/yourCars", [
            "yourCars" => CarResource::collection($cars),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Cache::remember("tags:index", 60, function() {
            return Tag::select("id", "name")->get();
        });

        $types = Cache::remember("types:index", 60, function() {
            return Type::select("id", "name")->get();
        });

        return Inertia::render("Cars/Create", [
            "tags" => $tags,
            "types" => $types,
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

        Cache::tags(["cars"])->flush();
        /*
        Cache::forget("cars:show:{$car->id}");
        Cache::forget("cars:edit:{$car->id}");
        */

        return redirect()->route("cars.show", ["car" => $car]);
        //return redirect_to("cars.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //dd(auth()->user()->can('create', \App\Models\Car::class));

        $car = Cache::tags(["cars"])->remember("show:{$car->id}", 60, function() use ($car){
            return $car->load(['owner', 'modifications', 'story', 'tags', 'types', "media"]);
        });

        return Inertia::render("Cars/Show", [
            "car" => CarResource::make($car),
            "can" => [
                "update" => auth()->user() ? auth()->user()->can(CarAbilities::UPDATE->value, $car) : false,
                "delete" => auth()->user() ? auth()->user()->can(CarAbilities::DELETE->value, $car) : false,
            ],
        ]);

        //cross site pt car->story cu purify
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $tags = Cache::remember("tags:index", 60, function() {
            return Tag::select("id", "name")->get();
        });

        $types = Cache::remember("types:index", 60, function() {
            return Type::select("id", "name")->get();
        });

        $car = Cache::tags(["cars"])->remember("edit:{$car->id}", 60, function() use ($car) {
            return $car->load(['owner', 'modifications', 'story', 'tags', 'types', "media"]);
        });

        return Inertia::render("Cars/Edit", [
            "car" => CarResource::make($car),
            "tags" => $tags,
            "types" => $types,
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

        Cache::tags(["cars"])->flush();

        return redirect()->route("cars.show", ["car" => $car]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();

        Cache::forget("cars:index");
        Cache::forget("cars:show:{$car->id}");
        Cache::forget("cars:edit:{$car->id}");

        return redirect()->route("cars.index");
    }

    public function destroyPhoto(Car $car, $photo_id) {
        //dd($car->id, $photo_id);
        $media = $car->media()->where("id", $photo_id)->where("collection_name", "photos")->first();

        if(!$media) {
            abort(404, "Photo not found");
        }

        $media->delete();

        Cache::tags(["cars"])->flush();

        return redirect("cars.edit", ["car" => $car]);
    }
}
