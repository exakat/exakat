<?php

$expected     = array('$a4 = (foo($d) AND $c) or $e',
                      '$a3 = foo($d) XOR $c',
                      '$a2 = 8 or $c',
                      '$a1 = $b and $c',
                     );

$expected_not = array('$b5 = (foo($d) AND $c)',
                      'foo($d) AND ($b5 = $c)',
                      '$b7 = $a->foo($d) && $c',
                     );

?>