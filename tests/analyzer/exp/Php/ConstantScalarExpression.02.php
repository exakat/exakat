<?php

$expected     = array('function a($name, $parameters = array( ), $absolute = false, $generator = null) { /**/ } ', 
                      'function a2($name, $parameters = ONE + 2, $absolute = false, $generator = null) { /**/ } ',);

$expected_not = array('function a3($name, $parameter, $absolute = false, $generator = null) { /**/ } ', );

?>