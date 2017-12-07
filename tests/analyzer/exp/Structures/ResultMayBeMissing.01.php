<?php

$expected     = array('preg_match(\'/PHP1/\', $string, $r)',
                      'preg_match(\'/PHP3/\', $string, $z)',
                     );

$expected_not = array('preg_match(\'/PHP2/\', $string, $r)',
                      'preg_match(\'/PHP4/\', $string, $r)',
                     );

?>