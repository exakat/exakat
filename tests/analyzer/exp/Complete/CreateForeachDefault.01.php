<?php

$expected     = array('foreach($a as $c => $d) { /**/ } ',
                      'foreach($a as $c => $d) { /**/ } ',
                      'foreach($a as $b) { /**/ } ',
                     );

$expected_not = array('foreach(foo( ) as $c => $d) { /**/ } ',
                     );

?>