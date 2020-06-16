<?php

$expected     = array('function foo2(array &$a2 = array( ), Stdclass &$b2 = null, \\Stdclass &$b3 = null, Callable &$c2 = null) { /**/ } ',
                      'function foo(array &$a, Stdclass &$b, Callable &$c) { /**/ } ',
                     );

$expected_not = array('array &$a',
                      'array &$a2',
                      'Callable &$c2',
                      'Callable &$c',
                      'function (&$b2 = null) { /**/ } ',
                      'function (string &$b3 = null) { /**/ } ',
                     );

?>