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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

    Route::get('/yourCars', [CarController::class, 'yourCars'])->name('cars.yourCars');
    Route::delete('/cars/{car}/photo/{photo}', [CarController::class, 'destroyPhoto'])->name('cars.destroyPhoto');


    //maybe..
    Route::middleware("can:manage-users")->prefix("admin")->group(function() {
        Route::resource('users', UsersController::class)->except([ 'create', 'store']);
        Route::resource("permissions", PermissionsController::class)->except(["show"]);
        Route::resource("groups", GroupsController::class)->except(["show"]);
    });

});

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');


require __DIR__.'/auth.php';


//midleware pt super-admin sa paota accesa /admin/plm
