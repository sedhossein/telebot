<?php

define('BOT_START', microtime(true));

// autoload + booting
require_once __DIR__.'/Core/autoload.php';

// run project
require_once __DIR__.'/Core/bot.php';
