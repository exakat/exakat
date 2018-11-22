<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin', '*');
header([1, 2 => 3, 'Access-Control-Allow-Origin', '*']);
header([1, 2 => 3, 'Access-Control-Allow-Origin' => '*']);

HEADER('ACCESS-CONTROL-ALLOW-ORIGIN: *');
HEADER('ACCESS-CONTROL-ALLOW-ORIGIN', '*');
HEADER(['ACCESS-CONTROL-ALLOW-ORIGIN' => '*']);

header('access-control-allow-origin: *');
header('access-control-allow-origin', '*');
header(['access-control-allow-origin' => '*']);

header('X-Xss-: *');
header('access-control-allow-origin', '1');
header([1, '0', '*', 2 => 3, 'access-control-allow-origin']);

?>