<?php

$expected     = array('$a[0] == 1 or $a[0] != 2',
                      '$a[0] == 1 OR $a[0] != 4',
                      '$a[0] == 1 || $a[0] != 3',
                     );

$expected_not = array('$a[0] == 1 OR $b[0] != 5',
                      '$a[0] == 1 xor $a[0] != 2',
                     );

?>