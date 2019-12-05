<?php
global $config;
global $user;
global $request;
global $database;

$data_array = explode('@', $request->text);
$user->send_message($data_array[0]);

if ($data_array[0] == 'delete') {
    $user->send_message("message is delete@somthing:");
    $user->send_message($request->text);
}
