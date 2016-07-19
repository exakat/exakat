<?php

$expected     = array( 'class F extends C1 { /**/ } ', 
                       'class D extends C1 { /**/ } ');

$expected_not = array('class A extends \Exception { /**/ } ', 
                      'class B extends A { /**/ } ', 
                      'class C extends B { /**/ } ');

?>