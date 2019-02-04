<?php

$expected     = array('function (array &$a, Stdclass &$b, Callable &$c) { /**/ } ',
                      'function (array &$a2 = array( ), Stdclass &$b2 = null, \\Stdclass &$b3 = null, Callable &$c2 = null) { /**/ } ',
                     );

$expected_not = array('array &$a',
                      'array &$a2',
                      'Callable &$c2',
                      'Callable &$c',
                     );

?>