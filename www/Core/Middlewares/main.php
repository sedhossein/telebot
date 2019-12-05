<?php

global $config;
global $user;
global $request;
global $database;
global $logger;

if ($config['is_bot_active'] == false) {
    $user->send_message('کاربر عزیز، ربات در حال تعمیر و به روزرسانی است، لطفا صبور باشید و ساعاتی دیگر مراجعه کنید.');
    die;
}

if ($config['only_admin'] == false) {
    $user->send_message('کاربر عزیز، ربات در حال تعمیر و به روزرسانی است، لطفا صبور باشید و ساعاتی دیگر مراجعه کنید.');
    die;
}


if (is_bot_off()) {
    $user->send_message('bot is off');
    die;
}

if (is_block_user($user->chat_id)) {
    $user->send_message('you are block, call admin');
    die;
}
