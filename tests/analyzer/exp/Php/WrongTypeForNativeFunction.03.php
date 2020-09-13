<?php

$expected     = array('preg_match(\'/b/\', \'\', $f)',
                     );

$expected_not = array('preg_match(\'/b/\', \'\', $a2)',
                      'preg_match(\'/g/\', \'\', $g)',
                      'preg_match(\'/b/\', $f, $r1)',
                     );

?>