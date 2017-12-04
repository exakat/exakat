<?php

$expected     = array('class x2 implements ac, i { /**/ } ',
                      'class a implements ac, ac2 { /**/ } ',
                      'class z2 extends ac implements ac2 { /**/ } ',
                      'class x implements i, ac { /**/ } ',
                     );

$expected_not = array('class z extends ac implements i { /**/ } ',
                      'class y extends ac { /**/ } ',
                     );

?>