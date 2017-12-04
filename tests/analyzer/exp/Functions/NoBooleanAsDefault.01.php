<?php

$expected     = array('function fooi1($c = PHP_OS == 1 ? true : 2) ;',
                      'function foo($a = 1, $b = 2.0, $c = true) { /**/ } ',
                      'function fooc1($a = 1, $b = 2.0, $c = \\false) { /**/ } ',
                      'function foot1($c = (PHP_OS == 1 ? true : false)) { /**/ } ',
                     );

$expected_not = array('function fooi2($c = PHP_OS == 1 ? 1 : 2) ;',
                      'function foo2($a = 1, $b = \'s\', $c) { } ',
                      'function fooc2($a = 1, $b = 2.0, $c) { } ',
                     );

?>