<?php

$expected     = array('preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER & PREG_OFFSET_CAPTURE)',
                      'preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER - 1)',
                      'preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER * PREG_OFFSET_CAPTURE)',
                      'preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER - PREG_OFFSET_CAPTURE)',
                      'preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER ^ PREG_OFFSET_CAPTURE)',
                     );

$expected_not = array('preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)',
                      'preg_match_all(\'/./\', $string, $cellReferences, PREG_SET_ORDER + PREG_OFFSET_CAPTURE)',
                     );

?>