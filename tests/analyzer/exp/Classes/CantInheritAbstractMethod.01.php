<?php

$expected     = array('abstract class B extends A { /**/ } ',
                     );

$expected_not = array('abstract class B2 extends A2 { /**/ } ',
                      'abstract class A2 { /**/ } ',
                      'abstract class A { /**/ } ',
                     );

?>