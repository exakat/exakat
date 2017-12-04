<?php

$expected     = array('class A implements i { /**/ } ',
                     );

$expected_not = array('final class D extends B { /**/ } ',
                      'class B extends A { /**/ } ',
                      'class A { /**/ } ',
                      'class C extends B { /**/ } ',
                     );

?>