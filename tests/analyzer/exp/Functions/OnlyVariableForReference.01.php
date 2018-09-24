<?php

$expected     = array('foo(1, array( ))',
                      'foo(1, 2)',
                      'foo(1, x( ))',
                      'foo(1, C)',
                     );

$expected_not = array('foo($a, $b)',
                      'foo($a, $b[1])',
                      'foo($a, $b->d)',
                     );

?>