<?php

global $config;

$config = [
    // change to your bot token, check the readme to know how to create and get your bot token.
    'bot_token' => 'YOUR_BOT_TOKEN',

    // change it TRUE if wanna bot only answer to trusted ChatIDs(admins)
    'only_admin' => false,

    'is_bot_active' => true,

    'language' => 'fa',

    // super_admin_ids
    'admin_ids' => [
        // '11111','22222',
    ],

    'super_admin' => [
        ''
    ],

    'services' => [
        'sms' => [
            'url' => "https://XXX.com",
            'username' => "USERNAME",
            'password' => "PASSWORD",
        ],
    ],
];
