<?php

$expected     = array('$a . $b',
                      '" $a $b"',
                      '$d[$e]',);

$expected_not = array('CONSTANT',
                      '\\CONSTANT');

?>