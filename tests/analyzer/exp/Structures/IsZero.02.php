<?php

$expected     = array('$c - $c',
                      '-$d + $e + $d', 
                      '$d + $e - $d',
                      '-$c + $c', 
                      '$c + $d - $e - $c', 
                      '+$c + $c', 
                      '-$d + $d + $e', 
                      '$d - $d + $e',
                       );

$expected_not = array('$b[3] + $c6 + $d->foo(1,2,3) - $c6 + $b[3]',
                      '$d + $e + $d',
                      '$b[3] - $c5 + $d->foo(1,2,3) + $c5 + $b[3]',
                     );

?>