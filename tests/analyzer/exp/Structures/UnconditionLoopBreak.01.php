<?php

$expected     = array('foreach(explode(\',\', $a->b[$c]) as $d1) { /**/ } ',
                      'for($a = 2 ; $a < 100 ; ++$a) { /**/ } ',
                      'do { /**/ } while(foo($i1) < 0)',
                      'while (foo($i1) < 0) { /**/ } ',
                     );

$expected_not = array('foreach(explode(\',\', $a->b[$c]) as $e2) { /**/ } ',
                      'for($a = 2 ; $a < 10 ; ++$a) { /**/ } ',
                      'do { /**/ } while(foo($i2) < 0)',
                      'while (foo($i2) < 0) { /**/ } ',
                     );

?>