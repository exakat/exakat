<?php

$expected     = array('class x { /**/ } ',
                     );

$expected_not = array('class x2 { /**/ } ',
                      'class x3 { /**/ } ',
                      'class y21 extends x2 { /**/ } ',
                      'class y22 extends x2 { /**/ } ',
                      'class y31 extends x3 { /**/ } ',
                      'class y32 extends x3 { /**/ } ',
                     );

?>