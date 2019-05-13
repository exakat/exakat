<?php

$expected     = array('preg_match_all(\'/(a)(b)/\', $string, $r)',
                      'preg_match_all(\'/(a)(c)/\', $string, $r, \PREG_PATTERN_ORDER)',
                      'preg_match_all(\'/(a)(e)/\', $string, $r, preg_set_order)',
                     );

$expected_not = array('preg_match_all(\'/(a)(c)/\', $string, $r)',
                      'preg_match_all(\'/(a)(d)/\', $string, $r)',
                      'preg_match_all(\'/(a)(e)/\', $string, $r, preg_pattern_order)'
                     );

?>