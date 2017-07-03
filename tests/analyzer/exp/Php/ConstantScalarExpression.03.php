<?php

$expected     = array('function a2($name, $parameters = [1, 2, 3]) { /**/ } ', 
                      'function a($name, $parameters = array( )) { /**/ } ', 
                      'function a3($name, $parameters = 1 + 2) { /**/ } ');

$expected_not = array('function a4($name, $parameters) { /**/ } ', );

?>