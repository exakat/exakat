<?php

$expected     = array('$a = &$b ** 3',
                      '$a = &$b && true',
                      '$a = &$b * $c',
                      '$a = &$b >> $c',
                      '$a = &$b > $c',
                     );

$expected_not = array('$a = &$c',
                     );

?>