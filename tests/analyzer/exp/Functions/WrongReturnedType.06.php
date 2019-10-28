<?php

$expected     = array('return C',
                      'return \'dfdfs\'',
                      'return (string) $c1',
                      'return (int) $c',
                      'return $c ? 5.6 : \'dfdfs\'',
                     );

$expected_not = array('return $c1 + $c2',
                      'return $c1 * $c2',
                      'return $c1 / $c2',
                      'return $c1 ** $c2',
                      'return (int) $c2',
                     );

?>