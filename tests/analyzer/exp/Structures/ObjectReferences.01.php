<?php

$expected     = array('function x(a &$b, c $d, &$e, &$f) { /**/ } ',
                      '$g',
                      '$k',
                      'function x(a &$b, c $d, &$e, &$f) { /**/ } ',
                      '$v2',
                     );

$expected_not = array('$d',
                      '$j',
                      '$h',
                      '$i',
                      '$i2',
                     );

?>