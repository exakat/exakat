<?php

$expected     = array('foreach($f->g as $h1) { /**/ } ',
                      'foreach($f->g as $h0) { /**/ } ',
                     );

$expected_not = array('foreach($a as $k => $v) { /**/ } ',
                      'foreach($f->g as $h2) { /**/ } ',
                     );

?>