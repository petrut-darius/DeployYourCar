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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        $car->load(['owner', 'modifications', 'story', 'tags', 'types']);

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
