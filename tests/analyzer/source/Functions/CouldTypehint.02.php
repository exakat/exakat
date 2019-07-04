<?php

function foo($a) {
    if (is_int($a)) { return $a;}
}

function bar($b) {
    if (is_int($b)) { $c = $b;}
}

function foobar($b) {
    if (!is_array($b)) { throw $c;}
}

function foobar2(&$c  = 2) {
    if (!is_resource($b)) { throw $c;}
}

?>