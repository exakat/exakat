<?php

$expected     = array('function __get($a) { /**/ } ',
                      'function xFoo( ) { /**/ } ',
                      'function foo( ) { /**/ } ',
                      'function ($a) { /**/ } ',
                     );

$expected_not = array('function xFoo2( ) { /**/ } ',
                     );

?>