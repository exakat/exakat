<?php

$expected     = array('f($deep3 = $t->strlen($de))',
                      '$b = 1 + ($deep1 = 3)',
                      'if($x2 = $y2 > 22 and $deep42 = 42) { /**/ } ',
                      'if($x = $y > 2 && $deep4 = 4) { /**/ } ',
                      '$a = ord($b[$deep2 = strlen($d) - 1])',
                     );

$expected_not = array('$normal1 = 2',
                      '$normal = 3;',
                      '$x = $y > 2',
                     );

?>