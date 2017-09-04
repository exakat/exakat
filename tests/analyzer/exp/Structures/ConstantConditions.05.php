<?php

$expected     = array('if(!defined(\'B\')) { /**/ } ', 
                      'if(getrandmax( )) { /**/ } ',
                      'if(srand(\'C\')) { /**/ } ', 
                      'if(strtolower(\'C\')) { /**/ } ',
                      );

$expected_not = array('if(mt_rand(\'C\')) { /**/ } ', 
                     );

?>