<?php

global $config;
global $user;
global $request;
global $database;

$user->send_message('welcome to admin hall');

if( preg_match('/@/is', $request->text) ){
	require_once __DIR__.'/at_sign_messages.php';
	exit;
}
