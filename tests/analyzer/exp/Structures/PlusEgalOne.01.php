<?php

$expected     = array('$b += 1',
                      '$c -= 1',
                      '$a = 1 + $a',
                      '$a = $a + 1',
                     );

$expected_not = array('$e -= 2',
                      '$f **= -1',
                     );

?>