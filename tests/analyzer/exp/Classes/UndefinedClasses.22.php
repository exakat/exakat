<?php

$expected     = array('function foo(parent $x) { /**/ } ',
                     );

$expected_not = array('function foo(parent $y) { /**/ } ',
                      'function foo(parent $z) { /**/ } ',
                     );

?>