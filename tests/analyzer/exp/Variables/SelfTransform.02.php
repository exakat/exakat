<?php

$expected     = array('$x3::$b', 
                      '$x3::$b', 
                      '$x3::$b', 
                      '$x5::$b', 
                      '$x5::$b', 
                      '$z7::$d', 
                      '$z7::$d', 
                      '$x', 
                      '$x', 
                      '$x2->a',
                      '$x2->a',
                     );

$expected_not = array('$x4::$b',
                      '$x[3]',
                     );

?>