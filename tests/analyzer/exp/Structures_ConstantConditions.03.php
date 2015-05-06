<?php

$expected     = array('for($i = 0 ; ; ;  ) { /**/ } ', 
                      'for($i = 0 ; ; ; $i++) { /**/ } ',
                      'for($i = 0 ;   ; $i++) { /**/ } ');

$expected_not = array('for ( ; $j < 10 ; $j++) {} ',
                      'for ( $i = 0; f() ; $i++) {} ');

?>