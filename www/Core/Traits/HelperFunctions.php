<?php

// REST
function sms_to($numbers = "", $message = "")
{
    global $config;

    if (empty($numbers) || empty($message)) {
        die('empty number or message');
    }

    $url = $config['services']['sms']['url'];
    $username = $config['services']['sms']['username'];
    $password = $config['services']['sms']['password'];

    // make query
    $query = [];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
    $res = curl_exec($ch);

    return json_encode($res);
}

//from database, admin was off the bot
function is_bot_off()
{
    global $database;

    $bot_status = $database->select("setting", "value", [
        "key" => "bot_status",
    ]);

    $bot_status = unserialize($bot_status[0]);

    return $bot_status['is_off'];
}

function is_block_user($user_id)
{
    global $database;

    return $database->count("users", [
        "user_id" => "$user_id",
        "is_block" => 1,
    ]);
}

// moving to better location
function is_admin_user($user_id)
{
    global $database;

    return $database->count("users", [
        "user_id" => "$user_id",
        "rule" => 'admin',
        "its_ok" => 1,
    ]);
}

function is_authorized_user($user_id)
{
    global $database;

    return $database->count("users", [
        "user_id" => "$user_id",
        "its_ok" => 1,
    ]);
}

function is_new_user($user_id)
{
    global $database;
    return $database->count("users", [
        "user_id" => "$user_id",
    ]);
}

function convert_persian_number_to_english($string)
{
    return strtr($string, [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9'
        ]
    );
}
