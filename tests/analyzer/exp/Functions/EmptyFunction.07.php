<?php

$expected     = array('function Foo1(int $d = 2) { /**/ } ', 
                      'function Foo2(int $d = 2) { /**/ } ', 
                      'function Foo3(int $d = 2) { /**/ } ', 
                      'function Foo4(int $d = 2) { /**/ } ', 
                      'function Foo6(int $d = 2) { /**/ } ', 
                      'function foo1(int $d = 2) { /**/ } ', 
                      'function foo2(int $d = 2) { /**/ } ', 
                      'function foo3(int $d = 2) { /**/ } ', 
                      'function foo4(int $d = 2) { /**/ } '
                     );

$expected_not = array('function Foo5(int $d = 2) { /**/ } ', 
                      'function foo5(int $d = 2) { /**/ } ', 
                     );

?>