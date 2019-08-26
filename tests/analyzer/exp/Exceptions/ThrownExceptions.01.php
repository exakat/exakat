<?php

$expected     = array('class x1 extends Exception { /**/ } ',
                      'class x2 extends Exception { /**/ } ',
                      'class x3 extends Exception { /**/ } ',
                      'class x4 extends Exception { /**/ } ',
                      'class x5 extends Exception { /**/ } ',
                     );

$expected_not = array('class x5 extends Exception { /**/ } ',
                      '$b',
                      'throw($b)',
                     );

?>