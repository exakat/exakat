<?php

$expected     = array('$array',
                      '$a1',
                      '$w',
                     );

$expected_not = array('$a',
                      '$b',
                      '$f', // This is a false positive
                      '$g',
                     );

?>