<?php

$expected     = array('$a = array( )',
                      '$b = array( )',
                      '$d = array( )',
                      '$e = array( )',
                      '$x->y = array( )',
                     );

$expected_not = array('$e[45]',
                      '$a[2]',
                      '$a[3]',
                      '$b[2]',
                      '$d[2]',
                      '$x->y[33]',
                      '$x->y[333]',
                     );

?>