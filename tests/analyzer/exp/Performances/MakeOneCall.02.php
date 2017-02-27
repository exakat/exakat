<?php

$expected     = array('$a = str_replace($a1, $b1, $c)');

$expected_not = array('$a = str_replace($a1, $b1, $c2)',
                      '$a = str_replace($a1, $b1, $c3)');

?>