<?php

$expected     = array('foreach($am1 as $im1 => $bm1) { /**/ } ',
                     );

$expected_not = array('foreach($anm1 as $inm1 => $bnm1) { /**/ } ',
                      'foreach($anm1 as $bnm2) { /**/ } ',
                      'foreach($anm1 as $inm3 => $bnm3) { /**/ } ',
                     );

?>