<?php

$expected     = array('if ( 6) { /**/ }  else if ( 8) { /**/ } ', 
                      'if ( 5) { /**/ }  else if ( 6) { /**/ } ', 
                      'if ( 4) { /**/ }  else if ( 5) { /**/ } ', 
                      'if ( 4) { /**/ } ', 
                      'for(7 ;   ;  ) { /**/ } ', 
                      'foreach($a2 as $b2){ /**/ } ', 
                      'while (7) { /**/ } ');

$expected_not = array('if ( 14) { /**/ }  else if ( 15) { /**/ } ', 
                      'if ( 14) { /**/ } ',
                      'for(7 ;   ;  ) { /**/ } ', 
                      'foreach($a2 as $b2){ /**/ } ', 
                      'while (7) { /**/ } ');

?>