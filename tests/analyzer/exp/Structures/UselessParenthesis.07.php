<?php

$expected     = array('function foo($c = (PHP_OS == 1 ? true : false)) { /**/ } ',
                     );

$expected_not = array('$d = 1',
                      '$e = (PHP_OS == 1 ? true : false) + 1',
                     );

?>