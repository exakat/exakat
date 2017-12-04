<?php

$expected     = array('for(7 ;   ;  ) /**/ ',
                      'foreach($a2 as $b2) /**/ ',
                      'while (7) /**/ ',
                      'elseif(8)  /**/  else  /**/  ',
                      'elseif(6)  /**/  elseif(8)  /**/  else  /**/  ',
                      'elseif(5)  /**/  elseif(6)  /**/  elseif(8)  /**/  else  /**/  ',
                      'if(4)  /**/  elseif(5)  /**/  elseif(6)  /**/  elseif(8)  /**/  else  /**/  ',
                     );

$expected_not = array('if ( 14) { /**/ }  else if ( 15) { /**/ } ',
                      'if ( 14) { /**/ } ',
                      'for(7 ;   ;  ) { /**/ } ',
                      'foreach($a2 as $b2){ /**/ } ',
                      'while (7) { /**/ } ',
                     );

?>