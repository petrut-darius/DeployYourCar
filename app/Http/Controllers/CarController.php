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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }
}
