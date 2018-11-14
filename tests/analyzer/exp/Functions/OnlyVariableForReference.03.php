<?php

$expected     = array('$this->foo(1, array( ))', 
                      '$this->foo(1, x( ))', 
                      '$this->foo(1, C)', 
                      '$this->foo(1, 2)',
                     );

$expected_not = array('$this->foo($a, $b)',
                      '$this->foo($a, $b[1])',
                      '$this->foo($a, $b->d)',
                     );

?>