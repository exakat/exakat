<?php

$expected     = array('\'Access-Control-Allow-Origin\' => \'*\'', 
                      '\'access-control-allow-origin\' => \'*\'', 
                      '\'ACCESS-CONTROL-ALLOW-ORIGIN\' => \'*\'', 
                      'header(\'Access-Control-Allow-Origin\', \'*\')', 
                      'header(\'access-control-allow-origin\', \'*\')', 
                      'HEADER(\'ACCESS-CONTROL-ALLOW-ORIGIN\', \'*\')', 
                      '\'Access-Control-Allow-Origin: *\'', 
                      '\'access-control-allow-origin: *\'', 
                      '\'ACCESS-CONTROL-ALLOW-ORIGIN: *\'',
                     );

$expected_not = array('header(\'X-Xss-: *\')',
                      'header(\'access-control-allow-origin\', \'1\')',
                      'header([1, \'*\', 2 => 3, \'access-control-allow-origin
                      \'])',
                     );

?>