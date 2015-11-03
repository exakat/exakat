<?php

$expected     = array('for($a = 1 ; $a < 5, $b++ ; $a++) { /**/ } ',
                      'for($a = 1, $b = 2, $c = 3 ; $d !== false, $e = 2 ; $e++, $f = $g->g($i)) { /**/ } ', 
                      'for($a = 1, $b = 2, $c = 3 ; $d !== false ; $e++) { /**/ } ', 
                      'for($a = 1 ; $d !== false ; $e++, $b = 2, $c = 3) { /**/ } ');

$expected_not = array('for ($a = 1; $b < 2; $c++) { /**/ } ');

?>