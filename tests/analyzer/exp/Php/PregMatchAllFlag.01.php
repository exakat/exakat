<?php

$expected     = array('preg_match_all(\'/(a)(b)/\', $string, $r)',
                     );

$expected_not = array('preg_match_all(\'/(a)(c)/\', $string, $r)',
                      'preg_match_all(\'/(a)(d)/\', $string, $r)',
                     );

?>