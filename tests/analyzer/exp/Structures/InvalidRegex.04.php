<?php

$expected     = array('preg_match(\'/\' . C . E . \'/\', $a, $b)',
                      'preg_match(\'/\' . C . D . \'/\', $a, $b)',
                     );

$expected_not = array('preg_match(\'/\' . B . E . \'/\', $a, $b)',
                      'preg_match(\'/\' . A . \'/\', $a, $b)',
                     );

?>