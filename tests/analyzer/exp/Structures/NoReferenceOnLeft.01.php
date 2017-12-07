<?php

$expected     = array('$a = &$b + $c',
                      '$a = &$b->d + $c',
                      '$a = &$b[1]->d + $c',
                     );

$expected_not = array('$a = $b + $c',
                     );

?>