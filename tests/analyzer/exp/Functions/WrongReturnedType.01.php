<?php

$expected     = array('return \'foo\'',
                      'return "$a"',
                     );

$expected_not = array('return $a',
                      'return',
                      'return 2',
                      'return A',
                     );

?>