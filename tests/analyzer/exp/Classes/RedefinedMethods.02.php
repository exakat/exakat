<?php

$expected     = array('function ad2( ) { /**/ } ',
                      'function ad1( ) { /**/ } ',
                      'function AC( ) { /**/ } ',
                      'function BC( ) { /**/ } ',
                     );

$expected_not = array('function ac( ) { /**/ } ',
                      'function ab( ) { /**/ } ',
                      'function ad2( ) { /**/ } ',
                      'function ad1( ) { /**/ } ',
                      'function ax( ) { /**/ } ',
                      'function bx( ) { /**/ } ',
                      'function cx( ) { /**/ } ',
                     );

?>