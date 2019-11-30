<?php

use Medoo\Medoo;

global $main_classes;

// loading all classes
foreach ($main_classes as $class){
	require_once __DIR__.'/../Core/Classes/' . $class . '.php';
}

global $database;
global $database_config;
$database = new Medoo($database_config);// make db instance

global $request;
$request = new Request();

global $user;
$user= new User($request->chat_id);

global $logger;
$logger = new Log($database_config);
