<?php

$expected     = array('function foo1(A $a) { /**/ } ', 
                      'function foo2(A $a) { /**/ } ', 
                      'function foo21(A $a) { /**/ } ', 
                      'function foo22(A $a) { /**/ } ', 
                      'function foo23(A $a) { /**/ } ', 
                      'function foo3(?A $a = null) { /**/ } '
                     );

$expected_not = array('',
                      'function foo24(A $a) { /**/ } ', 
                      'function foo11($a) { /**/ } ', 
                     );

?>