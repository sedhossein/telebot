<?php

global $config;
global $database;
global $request;
global $user;

// Routing
if (is_admin_user($user->chat_id)) {
    require_once __DIR__ . '/Controllers/Admin/index.php';
    die;
} else if (is_authorized_user($user->chat_id)) {
    require_once __DIR__ . '/Controllers/Members/index.php';
    die;
} else {
    // new user => start bot or its_ok is false
    require_once __DIR__ . '/Controllers/Guess/index.php';

    // foreign user comes here
    // send to monitoring gp

    Log::insert([
        'success' => 1,
        'user_id' => $user->chat_id,
        'type' => 'forbidden_user',
        'title' => 'forbidden user',
        'more_info' => 'forbidden user using bot with' . $request->text,
    ]);
    die;
}
