<?php

$expected     = array('$a += null',
                      '$a += false',
                     );

$expected_not = array('$a = 1',
                      '$a += 0.5',
                      '$a += 0.2',
                      '$a += 0.0',
                      '$a += 1.1',
                      '$a += [1, 2, 3]',
                      '$a += 0.0',
                     );

?>