<?php

$expected     = array('[$a, \'foo\']',
                      '[\\x::class, \'FOO\']',
                      '[B, A]',
                      '[\\x::class, A]',
                     );

$expected_not = array('[\\x, A]',
                      '[\'x\', \'foo\',3]',
                     );

?>