<?php

function foo($a = 1, $b = 2.0, $c = true) {}

class bar {
    function foo2($a = 1, $b = 2.0, $c = \false) {}
}

trait t {
    function fo03($c = (PHP_OS == 1 ? true : false) ) {}
}

interface i {
    function fo04($c = PHP_OS == 1 ? 1 : 2);
}
?>