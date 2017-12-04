<?php

$expected     = array('for($i = 0 ; \'a\' . PHP_VERSION ; $i++) { /**/ } ',
                      'for($i = 0 ; \'a\' . PHP_VERSION ;  ) { /**/ } ',
                      'for($i = 0 ;   ; $i++) { /**/ } ',
                     );

$expected_not = array('for ( ; $j < 10 ; $j++) {} ',
                      'for ( $i = 0; f() ; $i++) {} ',
                     );

?>