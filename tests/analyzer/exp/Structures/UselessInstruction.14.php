<?php

$expected     = array('$c == 2 || $d == 2',
                      '4 ^ 5',
                      '2 | 3',
                     );

$expected_not = array('$a or $b = 1',
                      '$a = 1 && $b',
                      '5 & 6',
                     );

?>