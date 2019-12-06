<?php

$a = 'a';
define($a, 1);

foreach($b as $c) {
    define($c, 3);
}

const D = 'E';
define(D, 'F');

define('G', 'F');

//echo a, D, E, G;

?>