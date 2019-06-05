<?php

use const D as E;

    const A = 1;
    const B = 2;
    const C = 3;
    const D = 4;

static $a = array(
        \A => '1',
        \B => '2',
        \C => '3',
        \D => '4',
);

static $b = array(
        \A => '1',
        \B => '2',
        \B => '3',
        \D => '4',
        \D => '4',
        \D => '4',
        \D => '4',
);

static $c = array(
        \A => '1',
        \B => '2',
        \B => '3',
        \D => '4',
        \D => '4',
        E => '4',
);

?>