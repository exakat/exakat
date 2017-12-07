<?php

$expected     = array('$a += 0',
                      '$b -= 0',
                      '0 + 1',
                      '1 - 0',
                      '1 + 0',
                      '0 - 1',
                     );

$expected_not = array('$b2 *= 0',
                     );

?>