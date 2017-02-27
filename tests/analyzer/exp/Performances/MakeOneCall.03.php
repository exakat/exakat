<?php

$expected     = array('$a = str_ireplace($a1, $b1, $c)');

$expected_not = array('$a = str_ireplace($a1, $b1, $c2)',
                      '$a = str_ireplace($a1, $b1, $c3)');

?>