<?php

$expected     = array('a(1, 2)',
                      'a( )',
                      'a2(21, 22)',
                      'a3(31, 32)',
                      'a4($d, 42)',
                     );

$expected_not = array('a2()',
                      'a3()',
                      'a4()',
                     );

?>