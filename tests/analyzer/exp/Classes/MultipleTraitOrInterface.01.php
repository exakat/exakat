<?php

$expected     = array('class x1 implements i, i, i { /**/ } ',
                      'class x2 implements I1, I2, I3 { /**/ } ',
                      'class x { /**/ } ',
                      'class x4 { /**/ } ',
                      'class x5 { /**/ } ',
                     );

$expected_not = array('class x3 implements I1, i, ArrayAccess { /**/ } ',
                     );

?>