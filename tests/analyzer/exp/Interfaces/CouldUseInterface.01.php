<?php

$expected     = array('class foo { /**/ } ',
                      'class foo3 { /**/ } ',
                     );

$expected_not = array('class foo2 implements i { /**/ } ',
                      'class foo4 { /**/ } ',
                      'class foo5 { /**/ } ',
                      'class foo6 { /**/ } ',
                     );

?>