<?php

$expected     = array('return $b->c[3] + 1',
                      'return $b + 1',
                      'return $a',
                     );

$expected_not = array('return substr($x[\'analyzer\'], 0, 7) !== \'Common\'',
                     );

?>