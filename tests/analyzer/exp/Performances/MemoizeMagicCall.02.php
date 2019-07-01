<?php

$expected     = array('function foo4(x $b) { /**/ } ',
                      'function foo2(x $b) { /**/ } ',
                      'function foo3(x $b) { /**/ } ',
                     );

$expected_not = array('function foo1(x $b) { /**/ } ',
                      'function foo2a($b) { /**/ } ',
                      'function foo2a(x $b) { /**/ } ',
                      'function foo_split1(x $b) { /**/ } ',
                      'function foo_split2(x $b) { /**/ } ',
                      'function foo_rw(x $b) { /**/ } ',
                     );

?>