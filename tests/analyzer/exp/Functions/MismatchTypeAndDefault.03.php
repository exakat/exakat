<?php

$expected     = array('function foo10(int $s = STRING) { /**/ } ',
                      'function foo11(int $s = \\STRING) { /**/ } ',
                      'function foo4(int $s = \'a\' + \'b\') { /**/ } ',
                      'function foo15(int $s = i::STRING) { /**/ } ',
                      'function foo16(int $s = i::ARRAY) { /**/ } ',
                     );

$expected_not = array('function foo7(bool $s = Null) { /**/ } ',
                      'function foo8(bool $s = True) { /**/ } ',
                      'function foo9(bool $s = False) { /**/ } ',
                     );

?>