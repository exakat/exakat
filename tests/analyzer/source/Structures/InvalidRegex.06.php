<?php

$a = "";
preg_replace_callback('/^(.*)\\\\([^\\]+)$/', function ($r) { return true; } , $a1);
preg_replace_callback('/^(.*)\\\\([^\\\\]+)$/', function ($r) { return true; } , $a2);
preg_replace_callback('/^(.*)\\\\([^\\\\]/++)$/', function ($r) { return true; } , $a3);

preg_replace_callback("/^(.*)\\\\([^\\]+)$/", function ($r) { return true; } , $a4);
preg_replace_callback("/^(.*)\\\\([^\\\\]+)$/", function ($r) { return true; } , $a5);
preg_replace_callback("/^(.*)\\\\([^\\\\]/++)$/", function ($r) { return true; } , $a6);

preg_match('/[^a-z0-9_\\\\]/i', $a7);
preg_match('/[^a-z0-9_\\]/i', $a8);

preg_match("/[^a-z0-9_\\\\]/i", $a9);
preg_match("/[^a-z0-9_\\]/i", $a10);

?>