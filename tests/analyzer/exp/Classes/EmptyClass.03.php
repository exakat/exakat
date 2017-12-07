<?php

$expected     = array('class EmptyClass { /**/ } ',
                     );

$expected_not = array('class X extends Exception { /**/ } ',
                      'class Y extends X { /**/ } ',
                      'class Z extends Y { /**/ } ',
                      'class X1 extends \\Exception { /**/ } ',
                      'class Y1 extends X1 { /**/ } ',
                      'class Z1 extends Y1 { /**/ } ',
                     );

?>