<?php

$expected     = array('interface x extends i, i, i { /**/ } ',
                      'interface x2 extends i, i, \\i { /**/ } ',
                      'interface x3 extends i, a, j { /**/ } ',
                     );

$expected_not = array('interface x4 { /**/ } ',
                      'interface x5 extends i, k { /**/ } ',
                      'interface x6 extends i, k, a, m { /**/ } ',
                     );

?>