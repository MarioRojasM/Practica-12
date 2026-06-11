<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-panel', function (User $user) { // [cite: 81]
    return $user->rol === 'admin';
}); // [cite: 83]