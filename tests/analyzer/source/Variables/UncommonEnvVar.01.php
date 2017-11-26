<?php

$_ENV['UNUSUAL'] = 3;
$_ENV['PATH']['Not found'] = 3;
echo $_ENV; // $_ENV use as as whole : ignore

$_ENV['PATH'] = 3;
$_ENV["TEMP"] = 3;

$_env['TAMP'] = 4;
$_POST["TEMP"] = 3;

?>