<?php

$expected     = array('$a = $b or $c');

$expected_not = array('$b && $c',
                      '$b || $c',
                      '$b ^ $c');

?>