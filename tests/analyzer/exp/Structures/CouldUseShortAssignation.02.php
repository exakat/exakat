<?php

$expected     = array('$g[1] = $g[1] . $e',
                      '$d = $d . $e',
                      '$a = $a . $b . $c',
                     );

$expected_not = array('$f[1] = $f . $e',
                     );

?>