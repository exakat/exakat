<?php

$expected     = array('function foo13(bool $s = INTEGER) { /**/ } ',
                      'function foo14(bool $s = i::INTEGER) { /**/ } ',
                      'function foo11(bool $s = \\STRING) { /**/ } ',
                      'function foo12(bool $s = \\INTEGER) { /**/ } ',
                      'function foo10(bool $s = STRING) { /**/ } ',
                      'function foo15(bool $s = i::STRING) { /**/ } ',
                      'function foo16(bool $s = i::ARRAY) { /**/ } ',
                     );

$expected_not = array('function foo7(bool $s = Null) { /**/ } ',
                      'function foo8(bool $s = True) { /**/ } ',
                      'function foo9(bool $s = False) { /**/ } ',
                     );

?>