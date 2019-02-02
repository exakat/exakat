<?php

$expected     = array('$b * $c = 1',
                      '$b3 * ($c3 = 1)',
                     );

$expected_not = array('$b * $c = 2',
                      '$b4 * ($c4 = 3)',
                     );

?>