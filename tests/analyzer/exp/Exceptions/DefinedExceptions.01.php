<?php

$expected     = array('class A extends Exception { /**/ } ',
                      'class B extends A { /**/ } ',
                      'class E extends B { /**/ } ',
                      'class F extends E { /**/ } ',
                     );

$expected_not = array('class C',
                      'class D extends C { /**/ } ',
                     );

?>