<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InertiaToast\Facades\Toast;

class NotificationController extends Controller
{
    public function unread()
    {
        return auth()->user()
            ->unreadNotifications
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'data' => $n->data,
                    'created_at' => $n->created_at,
                ];
            });
    }

    public function markAsRead(string $id)
    {
        auth()->user()->unreadNotifications
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        Toast::success("You are up to date!");

        return response()->json(['ok' => true]);
    }
}
