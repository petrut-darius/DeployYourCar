<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        Gate::define("manage-users", function(User $user) {
            return $user->hasPermission("user:create");
        });

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                "can" => [
                    "manageUsers" => Auth::check() && Gate::allows("manage-users")
                ],
            ],
        ];

        logger([
            'user' => Auth::user()?->id,
            'has_permission' => Auth::user()?->hasPermission('user:create'),
            'gate_allows' => Gate::allows('manage-users'),
        ]);
    }
}
