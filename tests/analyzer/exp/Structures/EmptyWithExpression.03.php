<?php

$expected     = array('$a2 = A::B($b[1])[2]',
                      '$a3 = A::B($b[3])',
                     );

$expected_not = array('$a = $b[1][2]',
                      '$c = $d + 3 ',
                      '$e = $f->g',
                      '$h = I::$j',
                      '$k = I::L',
                     );

?>