<?php

$expected     = array('$b[$l] = $b[$l] + 1',
                      '$c[$d] = $c[$d] + 1',
                     );

$expected_not = array('$c[$d] = $e[0] + 1',
                      '$b[] = $b[$l] + 1',
                     );

?>