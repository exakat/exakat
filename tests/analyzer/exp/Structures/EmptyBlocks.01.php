<?php

$expected     = array('for($i = 0 ; $i < 12 ; $i++) { /**/ } ',
                      'for($i = 0 ; $i < 10 ; $i++) :  /**/  endfor',
                      'for($i = 0 ; $i < 11 ; $i++) /**/ ',
                     );

$expected_not = array('for($i = 0 ; $i < 13 ; $i++) { /**/ } ',
                      'for($i = 0 ; $i < 14 ; $i++) /**/ endfor',
                     );

?>