<?php

$expected     = array('$a = \'\'',
                      '$c = <<<HERE
    
    
HERE',
                      '$b = \'\' . "b"',
                      '$d = C',
                     );

$expected_not = array('$f = array( )',
                      '$e = E',
                     );

?>