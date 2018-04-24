<?php

$expected     = array('preg_match(\'/\' . C . \'/\', $a, $b)',
                     );

$expected_not = array('preg_match(\'/\' . B . \'/\', $a, $b)',
                      'preg_match(\'/\' . A . \'/\', $a, $b)',
                     );

?>