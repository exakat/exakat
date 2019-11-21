<?php

$expected     = array('function a3(int $flags = YES) : array { /**/ } ',
                     );

$expected_not = array('function a1(int $flags = \\GLOB_NOSORT) : array { /**/ } ',
                      'function a2(int $flags = GLOB_NOSORT) : array { /**/ } ',
                     );

?>