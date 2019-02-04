<?php

$expected     = array('$a = $a ?? $b',
                      '$d[\'d\']->y[3] = $d[\'d\']->y[3] ?? $d',
                     );

$expected_not = array('$c = $b ?? $c',
                      '$e = $E ?? $d',
                     );

?>