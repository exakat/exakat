<?php

$expected     = array('$a->x( )',
                      '$a->x(7, 8, 9, 10)',
                      '$a->x(4, 5, 6)',
                     );

$expected_not = array('$a->x(1)',
                      '$a->x(2, 3)',
                     );

?>