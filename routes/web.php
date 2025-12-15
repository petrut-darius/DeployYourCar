<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Car;
use App\Http\Resources\CarResource;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\GroupsController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get("/cars/search", [CarController::class, "search"])->name("cars.search");
Route::get("/cars/filters", [CarController::class, "filters"])->name("cars.filters");
Route::resource("/cars", CarController::class)->only(["index", "show"]);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //maybe..
    Route::middleware("can:manage-users")->prefix("admin")->group(function() {
        Route::resource('users', UsersController::class)->except([ 'create', 'store']);
        Route::resource("permissions", PermissionsController::class)->except(["show"]);
        Route::resource("groups", GroupsController::class)->except(["show"]);
    });


    //okey
    Route::resource("/cars", CarController::class)->except(["index", "show"]);
    Route::get("/yourCars", [CarController::class, "yourCars"])->name("cars.yourCars");
    Route::delete("/cars/{car}/photo/{photo}", [CarController::class, "destroyPhoto"])->name("cars.destroyPhoto");
});

require __DIR__.'/auth.php';


//midleware pt super-admin sa paota accesa /admin/plm
