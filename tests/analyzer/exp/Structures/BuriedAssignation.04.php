<?php

$expected     = array('$a = 1 + 2 * ($b = intval($c))',
                      'if(false != ($b = strtolower($c))) { /**/ } ',
                     );

$expected_not = array('C = 3',
                      'A = 1',
                      'B = 2',
                      '$y = null',
                      '$property = 1',
                      '$property2 = 3',
                     );

?>