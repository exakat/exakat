<?php

xdebug_is_enabled();

krumo($arr);

print_r($b);

Kint::dump($GLOBALS, $_SERVER); // pass any number of parameters

Debug::enable();

define('WP_DEBUG', true);
define('WP_NOT_DEBUG', true);

?>