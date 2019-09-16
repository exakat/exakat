<?php

$expected     = array('do { /**/ } while(!empty($a))',
                      'do { /**/ } while(!empty($a))',
                      'while (!empty($a)) { /**/ } ',
                      'while (!empty($a)) { /**/ } ',
                     );

$expected_not = array('while(count($a) == 0) { /**/ } ',
                     );

?>