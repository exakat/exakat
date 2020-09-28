<?php

$expected     = array('$a === X',
                      '$a === 1 + X',
                      '$a === 1 + ($a ? 0.4 : 3)',
                      '1.0 - 1.0 == $a',
                      '$a === 0.3',
                     );

$expected_not = array('$a != A',
                     );

?>