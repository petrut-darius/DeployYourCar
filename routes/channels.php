<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('User.{userId}.notifications', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
