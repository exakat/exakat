<?php

$expected     = array('function fooReturn($a) { /**/ } ', 
                      'function fooNull($a) { /**/ } ', 
                      'function fooVoidInt($a) { /**/ } ');

$expected_not = array('function fooVoidVoid($a) { /**/ } ',
                      'function fooVoid($a) { /**/ } ',
 );

?>