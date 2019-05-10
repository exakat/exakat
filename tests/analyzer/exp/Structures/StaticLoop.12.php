<?php

$expected     = array('foreach($c as $d->x) { /**/ } ',
                     );

$expected_not = array('foreach($a as $b->x) { /**/ } ',
                      'foreach($f as $g->x) { /**/ } ',
                     );

?>