<?php

$expected     = array('preg_match(\'/(a)(b)?/\', \'adc\', $r)',
                      'preg_match(\'/(a)(b)?d(c)?(d)?/\', \'adc\', $r)',
                     );

$expected_not = array('preg_match(\'/(a)(b)/\', \'adc\', $r)',
                      'preg_match(\'/(a)(b)?(d)/\', \'adc\', $r)',
                      'preg_match(\'/(a)(b)?d(c)/\', \'adc\', $r)',
                      'preg_match(\'/(a)b?/\', \'adc\', $r)',
                     );

?>