<?php

$expected     = array('preg_match(\'/abc-3/\', $r)',
                      'preg_match(\'/abc\\\\]/\', $r)',
                      'preg_replace(\'/abc\\\\\\\\/\', $r, $b)',
                     );

$expected_not = array('preg_replace(\'/abc./\', $r, $b)',
                      'preg_replace(\'/ab|c/\', $r, $b)',
                      'preg_replace(\'/abc{3,2}/\', $r, $b)',
                      'preg_replace(\'/ab?c/\', $r, $b)',
                      'preg_replace(\'/[abc]/\', $r, $b)',
                      'preg_replace(\'/ab+c/\', $r, $b)',
                      'preg_match(\'/abc\\]/\', $r)',
                      'preg_replace(\'/^abc/\', $r, $b)',
                      'preg_replace(\'/a(bc)/\', $r, $b)',
                      'preg_match_all(\'/abc?/\', $r, $b)',
                      'preg_replace(\'/ab*c/\', $r, $b)',
                     );

?>