<?php

$name = $_GET['name'];
$cookie = $_COOKIE['cookie'];

// 'archive' is the incoming variable, not 'file_name'
$file_name = $_FILES['archive']['file_name'];

// This is not reported, because it is from $_ENV.
$db_pass = $_ENV['DB_PASS'];

// This is not reported, because it is dynamic
$x = 'userId';
$userId = $_GET[$x];

?>