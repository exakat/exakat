<?php

$expected     = array('if(!defined(\'B\')) { /**/ } ', 
                      'if(ini_get_all( )) { /**/ } ', 
                      'if(mt_rand(\'C\')) { /**/ } ');

$expected_not = array("mt_rand('C')");

?>