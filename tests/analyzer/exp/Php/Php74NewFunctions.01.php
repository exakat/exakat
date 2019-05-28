<?php

$expected     = array('function mb_str_split($a, $b) { /**/ } ',
                      'function password_algos( ) { /**/ } ',
                     );

$expected_not = array('function password_algos($a) { /**/ } ',
                     );

?>