<?php
// set your timezone
date_default_timezone_set('Asia/Tehran');

// composer autoload
//require_once __DIR__ . '/../vendor/autoload.php';

// load helpers
require_once __DIR__ . '/../Core/Traits/HelperFunctions.php';

// import config variables
require_once __DIR__ . '/../Config/variables.php';
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__.'/../Config/main.php';

// call bootstrap
require_once __DIR__ . "/../Bootstrap/bootstrap.php";

// validation gate for requests
// note: add your conditions in middleware
require_once __DIR__ . '/../Core/Middlewares/main.php';
