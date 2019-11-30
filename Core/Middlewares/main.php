<?php

global $config;
global $user;
global $request;
global $database;
global $logger;

if ($config['is_bot_active'] == false) {
    $user->send_message('کاربر عزیز، ربات در حال تعمیر و به روزرسانی است، لطفا صبور باشید و ساعاتی دیگر مراجعه کنید.');

    // send to monitoring group
    $logger->info([
        'success' => 1,
        'user_id' => $user->chat_id,
        'type' => 'config_time',
        'title' => 'bot is disable',
        'more_info' => 'its in repairing mood with sys admin',
    ]);

    die;
}

if ($config['only_admin'] == false) {
    $user->send_message('کاربر عزیز، ربات در حال تعمیر و به روزرسانی است، لطفا صبور باشید و ساعاتی دیگر مراجعه کنید.');

    // send to monitoring group
    $logger->info([
        'success' => 1,
        'user_id' => $user->chat_id,
        'type' => 'config_time',
        'title' => 'Alien Request',
        'more_info' => 'robot is in admin mood, foreign user used the bot',
    ]);

    die;
}


if (is_bot_off()) {
    $user->send_message('bot is off');

    // send to monitoring group
    $logger->info([
        'success' => 1,
        'user_id' => $user->chat_id,
        'type' => 'off_time',
        'title' => 'bot is off',
        'more_info' => 'bot is off with admins',
    ]);

    die;
}

if (is_block_user($user->chat_id)) {
    $user->send_message('you are block, call admin');

    // send to monitoring group
    $logger->info([
        'success' => 1,
        'user_id' => $user->chat_id,
        'type' => 'off_time',
        'title' => 'bot is off',
        'more_info' => 'bot is off with admins',
    ]);

    die;
}
