<?php

$expected     = array( '$a2 = someFunction($b2)',);

$expected_not = array('$a = $a + 1',
                      '$a2 = $b2 + 1',
                      '$a2 = someFunction($b2)');

?>