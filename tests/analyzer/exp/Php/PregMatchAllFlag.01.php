<?php

$expected     = array('preg_match_all(\'/a7/\', $b, $r7, PREG_PATTERN_ORDER)', 
                      'preg_match_all(\'/a8/\', $b, $r8, \PREG_PATTERN_ORDER)', 
                      'preg_match_all(\'/a6/\', $b, $r6)', 
                      'preg_match_all(\'/a5/\', $b, $r5)'
);

$expected_not = array('preg_match_all(\'/a1/\', $b, $r1, PREG_SET_ORDER)', 
                      'preg_match_all(\'/a2/\', $b, $r2, \PREG_SET_ORDER)', 
                      );

?>