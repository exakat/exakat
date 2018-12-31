<?php

$expected     = array('array_map(\'foo\', $a)',
                      'array_map(\'x::foo\', $a)',
                      'array_map(array(\'\\\\x\', \'foo\'), $a)',
                     );

$expected_not = array('array_map(\'foo2\', $a)',
                     );

?>