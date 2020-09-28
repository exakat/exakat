<?php

$expected     = array('array_pad(1, 2, $x)',
                      'array_fill(1, 2, new x)',
                     );

$expected_not = array('array_pad(1, 3, $y)',
                      'array_pad(1, 4, $s)',
                     );

?>