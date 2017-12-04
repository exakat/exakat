<?php

$expected     = array('function mcEmpty( ) { /**/ } ',
                      'function mbEmpty( ) { /**/ } ',
                      'function macSurcharged( ) { /**/ } ',
                      'function maSurcharged( ) { /**/ } ',
                      'function mabSurcharged( ) { /**/ } ',
                      'function maEmpty( ) { /**/ } ',
                      'function ( ) { /**/ } ',
                      'function ThisIsEmpty( ) { /**/ } ',
                     );

$expected_not = array('function ThisIsNotEmpty(){ /**/ }',
                     );

?>