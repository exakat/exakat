<?php

const PASSWORD = 'password';

$a = ['password' => "secret"];
$a = ["pass$word" => "secret"];
$a = ['passwd' => "secret"];
$a = ['user' => 1, 'pass' => "secret"];
$a = ['user' => 1, PASSWORD => "secret"];
$a = ['user' => 1, 'ab' => "secret"];

?>