<?php

$expected     = array('function bar1(string $a) { /**/ } ', 
                      'function (string $a) { /**/ } ', 
                      'function foo_method( ) { /**/ } ', 
                      'function foo_method($b) { /**/ } ', 
                      '$c', 
                      '$b', 
                      '$a',
                     );

$expected_not = array('$a2',
                      'string $a',
                     );

?>