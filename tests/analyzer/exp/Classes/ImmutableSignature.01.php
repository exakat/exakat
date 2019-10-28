<?php

$expected     = array('function foo2($abc3) { /**/ } ',
                      'function foo2($abc2) { /**/ } ',
                      'function foo2($abc1) { /**/ } ',
                      'function foo2($ab1, $c2) { /**/ } ',
                      'function foo2($abcd4) { /**/ } ',
                      'function foo2($abcd3) { /**/ } ',
                      'function foo2($abcd2) { /**/ } ',
                      'function foo2($abcd1) { /**/ } ',
                      'function foo2($a) { /**/ } ',
                     );

$expected_not = array('function foo($ab2) { /**/ } ',
                     );

?>