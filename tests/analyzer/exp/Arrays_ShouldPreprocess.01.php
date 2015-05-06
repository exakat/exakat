<?php

$expected     = array('$d = array( )', 
                      '$a = array( )', 
                      '$e = array( )', 
                      '$x->y = array( )', 
                      '$b = array( )'
);

$expected_not = array('$e[45]',
                      '$a[2]',
                      '$a[3]',
                      '$b[2]',
                      '$d[2]',
                      '$x->y[33]',
                      '$x->y[333]'
                      );

?>