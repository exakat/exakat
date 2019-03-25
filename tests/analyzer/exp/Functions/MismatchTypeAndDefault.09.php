<?php

$expected     = array('function foo2(string $a = A * 2) { /**/ } ',
                     );

$expected_not = array('function foo(string $a = \Exception::class) { /**/ } ',
                     );

?>