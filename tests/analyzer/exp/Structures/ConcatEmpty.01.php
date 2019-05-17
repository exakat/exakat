<?php

$expected     = array('$f .= \'\'',
                      '$b . \'\'',
                      '\'\' . $a',
                      'EMPTY1 . $e',
                      'EMPTY2 . $e',
                     );

$expected_not = array('\'a\' . $b . \'\' . $c . \'E\'',
                      'NOT_EMPTY . $e',
                     );

?>