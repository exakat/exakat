<?php

$expected     = array('$c - $c',
                      '-$d31 + $d31 + $e',
                      '-$d22 + $e + $d22',
                      '$d3 - $d3 + $e',
                      '$d + $e - $d',
                      '-$c + $c',
                      '$c + $d - $e - $c',
                      '-$c - $c',
                     );

$expected_not = array('$b[3] + $c6 + $d->foo(1,2,3) - $c6 + $b[3]',
                      '+$c + $c',
                      '$d + $e + $d',
                      '$b[3] - $c5 + $d->foo(1,2,3) + $c5 + $b[3]',
                     );

?>