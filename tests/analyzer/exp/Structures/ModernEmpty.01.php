<?php

$expected     = array('$a = strtolower($b . $c)', 
                      '$a2 = strtolower($b . $c)');

$expected_not = array('strtolower($b . $c)',
                      'strtolower($b0 . $c0)');

?>