<?php

$expected     = array('foreach($a as $b => $c) { /**/ } ',
                      'foreach($a as $b => $c3) { /**/ } ',
                     );

$expected_not = array('foreach($a as $b => $c2) { /**/ } ',
                     );

?>