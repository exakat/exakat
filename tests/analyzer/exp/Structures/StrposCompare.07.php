<?php

$expected     = array('preg_match(\'/\' . $s . \'/\', $a, $b)',
                      'preg_match("/$s/", $a, $b)',
                     );

$expected_not = array('preg_match(\'/asdf/\', $a, $b',
                      'preg_match_nope("/$s/", $a, $b)',
                     );

?>