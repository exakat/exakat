<?php

$expected     = array('if(!defined(\'B\')) { /**/ } ', 
                      'if(ini_get_all( )) { /**/ } ',
                      'if(srand(\'C\')) { /**/ } ', 
                      'if(strtolower(\'C\')) { /**/ } ');

$expected_not = array( 'if(mt_rand(\'C\')) { /**/ } ', );

?>