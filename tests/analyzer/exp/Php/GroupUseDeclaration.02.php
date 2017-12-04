<?php

$expected     = array('use some\\namespace1 { /**/ } ',
                      'use const some\\namespace3 { /**/ } ',
                      'use function some\\namespace2 { /**/ } ',
                     );

$expected_not = array('use function some\\namespace2\\fn_c',
                      'use some\\namespace1\\ClassC as C',
                     );

?>