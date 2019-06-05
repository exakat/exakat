<?php

$expected     = array('foreach($a->b as &$b) { /**/ } ', 
                      'foreach($a as &$b) { /**/ } ',
                     );

$expected_not = array('foreach($A as $B) { /**/ } ',
                      'foreach($a::$b as &$b) { /**/ } ',
                      'foreach(foo() as &$b) { /**/ } ',
                     );

?>