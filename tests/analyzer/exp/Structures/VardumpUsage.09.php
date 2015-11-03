<?php

$expected     = array('print_r($a, "1")', 
                      'print_r($a, "abc")', 
                      'print_r($a, \'1\')', 
                      'echo print_r($a, true)', 
                      'echo print_r($a, 1)');

$expected_not = array();

?>