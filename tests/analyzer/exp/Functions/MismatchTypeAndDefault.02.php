<?php

$expected     = array('function foo14(string $s = i::INTEGER) { /**/ } ',
                      'function foo16(string $s = i::ARRAY) { /**/ } ',
                      'function foo12(string $s = \\INTEGER) { /**/ } ',
                      'function foo13(string $s = INTEGER) { /**/ } ',
                     );

$expected_not = array('function foo15(string $s = i::STRING) { /**/ } ',
                     );

?>