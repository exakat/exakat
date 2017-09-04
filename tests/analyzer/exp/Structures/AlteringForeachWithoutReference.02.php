<?php

$expected     = array('foreach($contents4 as $id4 => $c4) { /**/ } ',
                      'foreach($contents3 as $id3 => $c3) { /**/ } ',
                     );

$expected_not = array('foreach($contents3 as $id1 => $c1) { /**/ } ',
                      'foreach($contents3 as $id2 => $c2) { /**/ } ',
                     );

?>