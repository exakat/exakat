<?php

$expected     = array('function test1(int $arg = CONST_RESOLVING_TO_NULL) { /**/ } ',
                     );

$expected_not = array('function test2(?int $arg = CONST_RESOLVING_TO_NULL) { /**/ } ',
                      'function test3(int $arg = null) { /**/ } ',
                     );

?>