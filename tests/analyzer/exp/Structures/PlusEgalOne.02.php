<?php

$expected     = array('$c -= 1',
                      '$a = $a - 1',
                      '$a4 = -1 + $a4',
                     );

$expected_not = array('$e = $f * 1',
                      '$a2 = $a2 - 2',
                      '$a3 = $b3 - 1',
                     );

?>