<?php

header('X-Xss-Protection: 0');
header('X-Xss-Protection', '0');
header([1, 2 => 3, 'X-Xss-Protection', '0']);
header([1, 2 => 3, 'X-Xss-Protection' => '0']);

HEADER('X-XSS-PROTECTION: 0');
HEADER('X-XSS-PROTECTION', '0');
HEADER(['X-XSS-PROTECTION' => '0']);

header('x-xss-protection: 0');
header('x-xss-protection', '0');
header(['x-xss-protection' => '0']);

header('X-Xss-: 0');
header('X-Xss-Protection', '1');
header([1, '0', 2 => 3, 'X-Xss-Protection']);

?>