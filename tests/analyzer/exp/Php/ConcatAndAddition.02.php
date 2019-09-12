<?php

$expected     = array('$a2 << $b2 . "sum: "',
                      '"sum: " . $a2 << $b2',
                      '$a1 >> $b1 . "sum: "',
                      '"sum: " . $a >> $b',
                     );

$expected_not = array('("sum: " . $a) >> $b',
                      '"sum :" . ($a << $b)',
                     );

?>