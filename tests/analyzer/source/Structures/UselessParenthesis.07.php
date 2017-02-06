<?php

function foo( $c = (PHP_OS == 1 ? true : false) ) {}
function bar( $d = 1, $e = (PHP_OS == 1 ? true : false) + 1 , $noDefault, stdClass $typehinted = null) {}

?>