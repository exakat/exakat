<?php

$expected     = array('return $a = 3',
                      'return $var = 2',
                      'return $array[2] = 2',
                     );

$expected_not = array('return A::$b = 3',
                      'return A::$b[3] = 3',
                      'return $a->a = 2',
                      'return $a3 = 3',
                      'return $a4 = 3',
                      'return $a2 = 2',
                     );

?>