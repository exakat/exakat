<?php

const C = '/c/';
define('D', '/d/');
$e = '/e/';

class x {
    const F = '/f/';
}

$g = 33;

preg_match('/a/', $r);
preg_match(C, $r);
preg_match(D, $r);
preg_match($e, $r);
preg_match(x::F, $r);
preg_match($g, $r);

?>