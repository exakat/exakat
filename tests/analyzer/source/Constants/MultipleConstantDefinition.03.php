<?php

define('a', 1);  // double definition
const a = 2;

define('B', 1, true); // Double definition
const b = 2;

define('B1', 1, true); // Double definition
const B1 = 2;

const c = 2;  // double definition
define('C', 1);

const d = 2;  // Double definition
define('D', 1, true);

const D2 = 2; // Double definition
define('D2', 1, true);

const e = 2;
define('f', 1, true);
define("G", 1);

?>