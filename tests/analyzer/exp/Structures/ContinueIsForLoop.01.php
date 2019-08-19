<?php

$expected     = array('switch ($b1) { /**/ } ',
                      'switch ($b3) { /**/ } ',
                     );

$expected_not = array('foreach($a1 as $b) { /**/ } ',
                      'foreach($a2 as $b) { /**/ } ',
                      'foreach($a3 as $b) { /**/ } ',
                      'foreach($a4 as $b) { /**/ } ',
                     );

?>