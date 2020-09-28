<?php

$expected     = array('foreach($o->m( ) as $o) { /**/ } ', 
                      'foreach($o->b as $o) { /**/ } ', 
                      'foreach($a[1] as $a) { /**/ } ',
                      'foreach($c::$b as $c) { /**/ } ',
                     );

$expected_not = array('',
                      'foreach(A as $A) { /**/ } ',
                      'foreach($B as $b) { /**/ } ',
                     );

?>


