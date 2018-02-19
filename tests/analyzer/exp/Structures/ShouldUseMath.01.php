<?php

$expected     = array('$a *= $a', 
                      '$a /= $a', 
                      '$a += $a', 
                      '$a -= $a', 
                      '$a %= $a'
                     );

$expected_not = array('$a = 0',
                      '$a .= $a',
                      '$a **= $a',
                     );

?>