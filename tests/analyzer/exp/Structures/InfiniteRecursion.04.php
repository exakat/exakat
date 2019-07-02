<?php

$expected     = array('function foobar( ) { /**/ } ',
                      'function bar2(x $a) { /**/ } ',
                      'function foo($a) { /**/ } ',
                     );

$expected_not = array('function bar($a) { /**/ } ',
                     );

?>