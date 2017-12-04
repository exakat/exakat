<?php

$expected     = array('class x4 extends x5 { /**/ } ',
                      'class x5 { /**/ } ',
                     );

$expected_not = array('class x1 extends Exception { /**/ } ',
                      'class x2 extends exception { /**/ } ',
                      'class x3 extends \\Exception { /**/ } ',
                     );

?>