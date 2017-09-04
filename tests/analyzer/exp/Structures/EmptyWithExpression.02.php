<?php

$expected     = array('$h = f(\'y\')',
                     );

$expected_not = array('$e = M_PI',
                      '$i = \\M_PI',
                      '$a = array(3,4)', 
                      '$b = null',
                      '$c = 1', 
                      '$d = 2.3',
                      '$e = M_PI',
                      '$f = array()',
                      '$g[1] = f(\'y\')',
                      );

?>