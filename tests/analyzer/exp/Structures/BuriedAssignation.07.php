<?php

$expected     = array('for( /**/  ; $i < $d ; $i++) { /**/ } ',
                      'for($i2 = 0 ;  /**/  ; $i++) { /**/ } ',
                     );

$expected_not = array('$i2 = 0',
                      '$i3 = 0',
                      '$b3 = count($f3)',
                     );

?>