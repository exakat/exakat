<?php

$expected     = array('function foo1($callable) { /**/ } ',
                     );

$expected_not = array('function foo2(callable $AlreadyCallable) { /**/ } ',
                      'function foo3(callable $AlreadyCallable2) { /**/ } ',
                      'function foo4($dunno) { /**/ } ',
                     );

?>