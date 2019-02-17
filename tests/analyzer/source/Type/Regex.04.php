<?php

const C = array('/c/');
define('D', array('/d/'));
$e = array('/e/');

class x {
    const F = array('/f/');
}

$g = array(3);

preg_match('/a/', $r);
preg_match(C, $r);
preg_match(D, $r);
preg_match($e, $r);
preg_match(x::F, $r);
preg_match($g, $r);

?>