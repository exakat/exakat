<?php

$expected     = array('class B extends A { /**/ } ',
                     );

$expected_not = array('class B1 extends A1 { /**/ } ',
                      'class B2 extends A2 { /**/ } ',
                      'class B3 extends A3 { /**/ } ',
                      'class A1 { /**/ } ',
                      'class A2 { /**/ } ',
                      'class A3 { /**/ } ',
                     );

?>