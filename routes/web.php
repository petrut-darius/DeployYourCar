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
use App\Models\User;

//move to controller when testing finished
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get("/user_pdf/{user}", function(User $user) {
    try{
        $user->load("groups");

        $permissions = \App\Models\Permission::whereIn("name", $user->permissions ?? [])->get();

        return Pdf::view("pdf.user", ["user" => $user, "permissions" => $permissions])->withBrowsershot(function (Browsershot $browsershot) {
            $browsershot->setNodeBinary('/home/thepid/.nvm/versions/node/v22.21.1/bin/node')->setNpmBinary('/home/thepid/.nvm/versions/node/v22.21.1/bin/npm')->setOption("args", [
                "--no-sandbox",
                "--disable-setuid-sandbox",
            ]);
        })->name("test.pdf")->download();
    } catch (\Throwable $e) {
        dd($e->getMessage(), $e->getTraceAsString()); // dump error
    }
})->name("users.pdf");


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
