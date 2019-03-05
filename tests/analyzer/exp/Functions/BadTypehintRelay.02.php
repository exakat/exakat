<?php

$expected     = array('function gooB(B $a) { /**/ } ',
                     );

$expected_not = array('function gooA(A $a) { /**/ } ',
                      'function foo(A $a) { /**/ } ',
                     );

?>