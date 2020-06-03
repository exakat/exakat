<?php

$expected     = array('foreach($b as &$c) { /**/ } ', 
                      'foreach($b as &$c2) { /**/ } ', 
                      'foreach($b as &$c3) { /**/ } ',
                     );

$expected_not = array('foreach($a as &$b) { /**/ } ',
                     );

?>