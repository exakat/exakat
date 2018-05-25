<?php

$expected     = array('foreach($a as $b[1] => $c->b) { /**/ } ',
                      'foreach($a as $b::$c => $c) { /**/ } ',
                      'foreach($a as $k => $c::$b) { /**/ } ',
                      'foreach($a as $c->d) { /**/ } ',
                      'foreach($a as $b->c => $c) { /**/ } ',
                      'foreach($a as $c::$d) { /**/ } ',
                     );

$expected_not = array('foreach($a::$b as $c) { /**/ } ',
                     );

?>