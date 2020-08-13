<?php

$expected     = array('function foo2($a, $b, $c = 1) { /**/ } ',
                     );

$expected_not = array('function foo2($a, $b) { /**/ } ',
                      'function foo1($a) { /**/ } '
                     );

?>