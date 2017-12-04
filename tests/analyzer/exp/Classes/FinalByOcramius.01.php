<?php

$expected     = array('class aOK implements i1 { /**/ } ',
                     );

$expected_not = array('class aKO1 implements i1 { /**/ } ',
                      'class aKO2 { /**/ } ',
                      'class aKO3 extends aKO2 { /**/ } ',
                      'class aKO4 extends aKO2 implements i1 { /**/ } ',
                     );

?>