<?php

$expected     = array('function ma3($b, $c) { /**/ } ', 
                      'function ma2($b) { /**/ } ', 
                      'function ma1( ) { /**/ } ',
                      'function ma3($z, $a, $b, $c) { /**/ } ', 
                      'function ma2($z, $a, $b) { /**/ } ', 
                      'function ma1($z, $a) { /**/ } ',
                      'function ma0($z) { /**/ } ',
                     );

$expected_not = array('function ma0($z) { /**/ } ',
                     );

?>