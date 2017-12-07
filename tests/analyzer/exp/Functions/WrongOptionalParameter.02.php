<?php

$expected     = array('function dasf2($name, $content, $headers = array( ), $error2) { /**/ } ',
                      'function test2($name, $status = 200, array $vars = array( ), $error) { /**/ } ',
                     );

$expected_not = array('function test($name, $status = 200, array $vars = array( )) { /**/ } ',
                      'function dasf($name, $content, $headers = array( )) { /**/ } ',
                     );

?>