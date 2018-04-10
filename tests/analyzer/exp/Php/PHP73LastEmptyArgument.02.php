<?php

$expected     = array('foo(1, 2, 3,  )', 
                      'foo(1, 2,  )', 
                      'foo(1,  )',
                     );

$expected_not = array('foo( )',
                      'foo(1, 2, 3  )', 
                      'foo(1, 2  )', 
                      'foo(1  )',
                     );

?>