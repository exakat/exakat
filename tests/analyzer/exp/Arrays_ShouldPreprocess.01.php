<?php

$expected     = array('$a[1]',
                      '$b[4]',
                      '$d[1]',
                      '$x->y[3]');

$expected_not = array('$e[45]',
                      '$a[2]',
                      '$a[3]',
                      '$b[2]',
                      '$d[2]',
                      '$x->y[33]',
                      '$x->y[333]'
                      );

?>