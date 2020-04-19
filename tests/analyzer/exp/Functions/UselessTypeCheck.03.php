<?php

$expected     = array('function f4(int $a4) { /**/ } ',
                      'function f2(?array $a2 = [ ]) { /**/ } ',
                     );

$expected_not = array('function f1(?array $a1 = null) { /**/ } ',
                      'function f2(?array $a2 = null) { /**/ } ',
                      'function f3(?array $a3 = null) { /**/ } ',
                     );

?>