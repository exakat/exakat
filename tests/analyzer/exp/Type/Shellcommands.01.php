<?php

$expected     = array('\'ls -2\'',
                      '\'ls -1\'',
                      '`ls -1 $x`',
                     );

$expected_not = array('$x',
                      'ls -3',
                     );

?>