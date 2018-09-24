<?php

$expected     = array('function  ($withThis) { /**/ } ',
                     );

$expected_not = array('function  ( ) { /**/ } ',
                      'function dontGetClosure( ) { /**/ } ',
                     );

?>