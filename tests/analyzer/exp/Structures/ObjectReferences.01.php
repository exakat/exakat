<?php

$expected     = array( 'function x(a &$b, c $d, &$e, &$f) { /**/ } ',
                      '$g',
                      '$k',
                      'function x(a &$b, c $d, &$e, &$f) { /**/ } ',
                     );

$expected_not = array('$d',
                      '$j',
                      '$h',
                      '$i',
                     );

?>