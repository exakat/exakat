<?php

$expected     = array('"b$c" . $d',
                      '$f . " {$c} "',
                      '"b$c" . $d . $e',
                      '$f . "b$c" . $d . $e',
                      '$ff . " {$cf[3]} "',
                     );

$expected_not = array(
                     );

?>