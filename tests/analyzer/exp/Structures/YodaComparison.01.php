<?php

$expected     = array('$a == null',
                      '$a == true',
                      '$a == +2',
                      '$a == 1',
                      '$d[3] === \\A\\SOME_YODA_CONSTANT',
                      '$a == 1.2',
                     );

$expected_not = array('$d[3] != YODA_SOME_CONSTANT',
                     );

?>