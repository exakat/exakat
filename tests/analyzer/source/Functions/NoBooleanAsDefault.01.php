<?php

function foo($a = 1, $b = 2.0, $c = true) {}
function foo2($a = 1, $b = 's', $c) {}

class bar {
    function fooc1($a = 1, $b = 2.0, $c = \false) {}
    function fooc2($a = 1, $b = 2.0, $c) {}
}

trait t {
    function foot1($c = (PHP_OS == 1 ? true : false) ) {}
    function foot2($c = (PHP_OS == 1 ? 2 : 3) ) {}
}

interface i {
    function fooi1($c = PHP_OS == 1 ? true : 2);
    function fooi2($c = PHP_OS == 1 ? 1 : 2);
}
?>