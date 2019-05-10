<?php

$expected     = array('function __SET($a, $b) { /**/ } ',
                      'function __set($a, $b) { /**/ } ',
                      'function __CLONE( ) { /**/ } ',
                      'function __clone( ) { /**/ } ',
                     );

$expected_not = array('',
                      'function __get($a) { /**/ } ',
                      'function __GET($a) { /**/ } ',
                     );

?>