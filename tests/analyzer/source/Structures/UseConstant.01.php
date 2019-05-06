<?php

if (php_version() > 3) {}

if (PHP_SAPI_NAME() > 3) {}

fopen('php://stdin', 'r');

fopen("php://stdout", 'w');

fopen('php://stderr', 'w');

$a->php_version();

\a\b\PHP_sapi_name();

fopen('file://etc/test.php', 'r');

?>