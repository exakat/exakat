<?php

$expected     = array('function foo( ) { /**/ } ',
                      'function bar( ) { /**/ } ',
                     );

$expected_not = array('function __get($ax) { /**/ } ',
                      'function __get($a) { /**/ } ',
                      'function __set($a, $b) { /**/ } ',
                     );

?>