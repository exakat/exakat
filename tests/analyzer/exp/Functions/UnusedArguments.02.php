<?php

$expected     = array('function C1(callable $d1, callable $e = null, callable $e2 = null, callable $e3) { /**/ } ',
                      'function C2(callable $d2, callable $e = null, callable $e2 = null, callable $e3) { /**/ } ',
                     );

$expected_not = array('function C3(callable $d3, callable $e = null, callable $e2 = null, callable $e3) { /**/ } ',
                     );

?>