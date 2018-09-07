<?php

$expected     = array('function ma1( ) { /**/ } ', 
                      'function ma2( ) { /**/ } ', 
                      'function ma2($a) { /**/ } ', 
                      'function ma3( ) { /**/ } ', 
                      'function ma3($a) { /**/ } ', 
                      'function ma3($a, $b) { /**/ } ', 
                     );

$expected_not = array('function ma0($a, $b, $c) { /**/ } ', 
                      'function ma0($a, $b) { /**/ } ', 
                      'function ma0($a) { /**/ } ',
                      'function ma1($a, $b) { /**/ } ', 
                      'function ma1($a, $b, $c) { /**/ } ', 
                      'function ma2($a, $b, $c) { /**/ } ', 
                     );

?>