<?php

$expected     = array('static function mixtedStatic( ) { /**/ } ',
                      'function mixtedStatic( ) { /**/ } ',
                     );

$expected_not = array('function noneStatic( ) { /**/ } ',
                      'function static allStatic( ) { /**/ } ',
                     );

?>