<?php

$expected     = array('A::foo(1, array( ))',
                      'A::foo(1, 2)',
                      'A::foo(1, x( ))',
                      'A::foo(1, C)',
                     );

$expected_not = array('A::foo($a, $b)',
                      'A::foo($a, $b[1])',
                      'A::foo($a, $b->d)',
                     );

?>