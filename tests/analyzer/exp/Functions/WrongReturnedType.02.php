<?php

$expected     = array('return $f', 
                      'return $b', 
                      'return $a', 
                      'return $b', 
                      'return 1', 
                      'new Bar( )', 
                      'new Foo( )',
                     );

$expected_not = array('return $b',
                      'return new Bar()',
                     );

?>