<?php

$expected     = array('foreach($x as $a1 => $b1) { /**/ } ',
                      'foreach($x as $a2 => $b2) { /**/ } ',
                      'foreach($x as $a4 => $b4) { /**/ } ',
                      'foreach($x as $b6) { /**/ } ',
                     );

$expected_not = array('foreach($x as $a3 => $b3) { /**/ } ',
                      'foreach($x as $a5 => &$b5) { /**/ } ',
                      'foreach($x as &$b7) { /**/ } ',
                     );

?>