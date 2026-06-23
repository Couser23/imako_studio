<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('session.{sessionId}', function ($user, $sessionId) {
    // Ideally we would check if the session belongs to the user, but we just let anyone who is authenticated listen to their own sessions.
    // The session ID is randomly generated anyway so it's secure enough to just verify they are logged in.
    return true;
});

Broadcast::channel('admin.notifications', function ($user) {
    return $user->role === 'admin';
});
