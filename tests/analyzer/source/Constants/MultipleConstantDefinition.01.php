<?php

define('a', 1);
define('a', 2);

define('b', 3);
define("b", 4);

define('c', 5); // OK
define('C', 6);

define('d', 5, true);
define('D', 6, true);

define('e', 7, true);
define("E", 8, true);

define('d2', 5);
define('D2', 6, true);

define('d3', 5, true);
define('D3', 6);

define('f', 9);
define('f', 10);
define('f', 11);

define('g', 12);
define('g', 13);
define('g', 14);
define('g', 15);

?>