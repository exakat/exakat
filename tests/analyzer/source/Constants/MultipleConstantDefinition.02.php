<?php

define("a$a", 1);
define("a$a", 2);

$a->define('b', 3);
A::define("b", 4);

define('c'.'d', 5);
define('C'.'d', 6);

define('f', 9);
const f = 10;

define('F1', 9);
const f1 = 10;

define('F2', 9, true);
const f2 = 10;

define('g', 12);
define('g', 11  + 2);

const h = 10;
const h = 10;

class x {
    const i = 10;
}
const i = 12;

const J = 10;
const j = 10;
?>