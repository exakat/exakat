<?php

$expected     = array('$a == +2',
                      '$a == true',
                      '$a == null',
                      '$a == 1.2',
                     );

$expected_not = array('$d[3] != YODA_SOME_CONSTANT',
                     );

?>