<?php

$expected     = array('$a[1][][3] = range(1, 2)',
                      '$a[1][][3] = foo(1, 2)',
                      '$a[1][2][3] = array( )',
                      '$a[1][][3][4] = array( )',
                      '$a[1][2][3][4]',
                      '$a[1][][3][4]',
                     );

$expected_not = array('$a[1][][3] = count(array(1,2,3))',
                      '$a[1][2]',
                      '$a[1][][3] = foo(1,2)',
                     );

?>