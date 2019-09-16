<?php

$expected     = array('\'a\' . $b ?? $c',
                      '$b ?? \'d\' . \'e\'',
                     );

$expected_not = array('$b ?? \'d\'',
                     );

?>