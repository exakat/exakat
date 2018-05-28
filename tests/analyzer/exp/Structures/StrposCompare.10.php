<?php

$expected     = array('preg_match(\'/\' . A::foo( ) . \'/\', $a, $b)',
                      'preg_match(\'/\' . foo( ) . \'/\', $a, $b)',
                      'preg_match(\'/\' . $s[2] . \'/\', $a, $b)',
                      'preg_match(\'/\' . $b->foo( ) . \'/\', $a, $b)',
                      'preg_match(\'/\' . $s->d . \'/\', $a, $b)',
                      'preg_match(\'/\' . $s . \'/\', $a, $b)',
                     );

$expected_not = array('preg_match(\'/asdf/\', $a, $b',
                      'preg_match_nope("/$s/", $a, $b)',
                     );

?>