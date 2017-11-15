<?php

set_error_handler(array('foo', 'a0'));

set_error_handler(array('foo', 'a'));

set_error_handler(array('foo', 'a14'));

set_error_handler(array('foo', 'a13'));

set_error_handler(array('foo', 'a12'));

class foo {
    function a0 ($a0, $b0, $c0, $d0, $e0, $f0) {}
    function a ($a, $b, $c, $d, $f) {}
    function a14($a1, $b1, $c1, $d1) {}
    function a13($a1, $b1, $c1) {}
    function a12($a1, $b1) {}
}
?>