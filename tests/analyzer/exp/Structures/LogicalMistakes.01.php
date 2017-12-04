<?php

$expected     = array('$a != 1 || $a != 3',
                      '$a != 1 OR $a != 4',
                      '$a != 1 or $a != 2',
                     );

$expected_not = array('$a != 1 OR $b != 5',
                      '$a != 1 xor $a != 2',
                     );

?>