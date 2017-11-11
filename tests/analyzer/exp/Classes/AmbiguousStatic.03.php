<?php

$expected     = array('static function mixtedStatic( ) { /**/ } ',
                      'function mixtedstatic( ) { /**/ } ',
                     );

$expected_not = array('function noneStatic( ) { /**/ } ',
                      'function static allStatic( ) { /**/ } ',
                     );

?>