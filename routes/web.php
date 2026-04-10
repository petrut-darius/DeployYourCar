<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepliesController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CarController;
use App\Http\Controllers\FollowingsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\LikesController;

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

Route::get("/users/{user}", [UsersController::class, "show"])->name("users.show");

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
        dd($e->getMessage(), $e->getTraceAsString());
    }
})->name("users.pdf");

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //cars
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

    Route::get('/yourCars', [CarController::class, 'yourCars'])->name('cars.yourCars');
    Route::delete('/cars/{car}/photo/{photo}', [CarController::class, 'destroyPhoto'])->name('cars.destroyPhoto');

    Route::post("/car/{car}/replies", [RepliesController::class, "storeForCar"])->name("replies.storeForCar");
    Route::post("/reply/{reply}/replies", [RepliesController::class, "storeForReply"])->name("replies.storeForReply");
    Route::delete("/replies/{reply}", [RepliesController::class, "destroy"])->name("replies.destroy");

    Route::post("/users/{user}/follow", [FollowingsController::class, "store"])->name("following.store");
    Route::delete('users/{user}/unfollow', [FollowingsController::class, "destroy"])->name("following.destroy");

    Route::post("/cars/{car}/like", [LikesController::class, "storeForCar"])->name("likeable.storeForCar");
    Route::delete("/cars/{car}/dislike", [LikesController::class,"destroyForCar"])->name("likeable.destroyForCar");

    Route::post("/replies/{reply}/like", [LikesController::class, "storeForReply"])->name("likeable.storeForReply");
    Route::delete("/replies/{reply}/dislike", [LikesController::class,"destroyForReply"])->name("likeable.destroyForReply");

    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);    
});

//needs to stay right here cause the show page blocks the create page
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

Route::get("/replies/{reply}/replies", [RepliesController::class, "replies"]);

require __DIR__.'/auth.php';
