<?php

$expected     = array('foreach($a6 as $b) { /**/ } ',
                      'foreach($a5 as $b) { /**/ } ',
                      'foreach($a4 as $b) { /**/ } ',
                      'foreach($a3 as $b) :  /**/  endforeach',
                      'foreach($a2 as $b) :  /**/  endforeach',
                      'foreach($a1 as $b) :  /**/  endforeach',
                     );

$expected_not = array('foreach($A2 as $b) { /**/ } ',
                      'foreach($A1 as $b) :  /**/  endforeach',
                     );

?>