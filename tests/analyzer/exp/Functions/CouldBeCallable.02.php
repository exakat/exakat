<?php

$expected     = array('function foo5($a, $b, $c, $callable) { /**/ } ',
                      'function foo3($calledTwice) { /**/ } ',
                      'function foo1($callable) { /**/ } ',
                     );

$expected_not = array('function foo2($callable) { /**/ } ',
                      'function foo4($AlreadyCallable2) { /**/ } ',
                     );

?>