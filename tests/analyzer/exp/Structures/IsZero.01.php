<?php

$expected     = array('$c - $c',
                      '1 - 1',
                      '$f[1 - 3] - $f[1 - 3]',
                      '$g1 + $h1 + $h1 - $g1', 
                      '$g3 + $h3 + -$g3', 
                      '$g2 + $h2 - $g2',
                     );

$expected_not = array('$d + $d',
                      '$d - $d',
                      '$f[1 - 3] + $f[1 - 3]',
                     );

?>