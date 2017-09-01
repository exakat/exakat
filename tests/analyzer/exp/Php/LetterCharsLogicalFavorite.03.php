<?php

$expected     = array('$b || $c',
                     );

$expected_not = array('$b and $c',
                      '$b And $c',
                      '$b or $c',
                      '$b OR $c',
                      '$b aND $c',
                      '$B | $C',
                      '$B & $C',
                      '$B ^ $C',
                     );

?>