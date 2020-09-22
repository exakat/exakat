<?php

$commands = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?? '');
$commands = explode('/', substr($a, $b1) ?? '');
$commands = explode('/', substr($a, $b2) ?: '');

$commands = explode('/', shell_exec($a, $b2) ?? '');
$commands = explode('/', shell_exec($a, $b2) ?: '');
$commands = explode('/', shell_exec($a, $b2));

$commands = explode('/', strpos($a, $b4) ?: '');
$commands = explode('/', strpos($a, $b4));

unlink(__FILE__);
