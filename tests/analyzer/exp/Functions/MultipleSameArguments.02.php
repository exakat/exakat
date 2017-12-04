<?php

$expected     = array('function D(array $e, array $e) { /**/ } ',
                      'function C(array $c = null, array $c = null) { /**/ } ',
                     );

$expected_not = array('B(array $a = null, array $b = null)',
                      'E ($g, $h = 0, array $i = array(1, 2, 3))',
                     );

?>