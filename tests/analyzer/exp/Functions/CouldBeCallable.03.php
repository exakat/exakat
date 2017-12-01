<?php

$expected     = array('function foo1($callable) { /**/ } ', 
                      'function foo3(string $callable) { /**/ } ',
                     );

$expected_not = array('function foo2(callable $callable) { /**/ } ', 
                      'function foo4(callable $callable) { /**/ } ', 
                      'function foo5($callable) { /**/ } ',
                     );

?>