<?php

$expected     = array('for(7 ;   ;  )  /**/ ', 
                      'if ( 4)  /**/ ', 
                      'if ( 5)  /**/ ', 
                      'if ( 6)  /**/ ', 
                      'if ( 8)  /**/ ', 
                      'foreach($a2 as $b2)  /**/ ',
                      'while (7)  /**/ '
                      );

$expected_not = array('if ( 14) { /**/ }  else if ( 15) { /**/ } ', 
                      'if ( 14) { /**/ } ',
                      'for(7 ;   ;  ) { /**/ } ', 
                      'foreach($a2 as $b2){ /**/ } ', 
                      'while (7) { /**/ } ');

?>