<?php

$expected     = array('function cmpUsed($a, $b) { /**/ } ',
                      'function cmpUsedFullnspath1($a, $b) { /**/ } ', 
                      'function cmpUsedFullnspath2($a, $b) { /**/ } ', 
                      'function cmpUsedFullnspath3($a, $b) { /**/ } ', 
                      'function cmpUsedFullnspath4($a, $b) { /**/ } ', 
                      'function b4( ) { /**/ } ', 
                      'function b5( ) { /**/ } ', 
                      'function b3( ) { /**/ } ', 
                      'function b2( ) { /**/ } ', 
                      'function b1( ) { /**/ } '
                     );

$expected_not = array('function cmpNotUsed($a, $b) { /**/ } ',
                     );

?>