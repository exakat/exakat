<?php

$expected     = array('foreach($a2 as $b => $c) { /**/ } ', 
                      'foreach($a as $b => $c) { /**/ } ',
                      'foreach($a1 as &$c) { /**/ } ',
                     );

$expected_not = array('foreach($A1 as $b => $c) { /**/ } ',
                     );

?>