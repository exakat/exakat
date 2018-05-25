<?php

$expected     = array('function foo15(array $s = i::STRING) { /**/ } ',
                      'function foo10(array $s = STRING) { /**/ } ',
                      'function foo11(array $s = \\STRING) { /**/ } ',
                      'function foo13(array $s = INTEGER) { /**/ } ',
                      'function foo12(array $s = \\INTEGER) { /**/ } ',
                      'function foo14(array $s = i::INTEGER) { /**/ } ',
                     );

$expected_not = array('function foo7(array $s = null) { /**/ } ',
                      'function foo16(array $s = i::ARRAY) { /**/ } ',
                      'function foo1(array $s = array( )) { /**/ } ',
                     );

?>