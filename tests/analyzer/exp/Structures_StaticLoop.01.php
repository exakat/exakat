<?php

$expected     = array('foreach($a as $b => $c) { /**/ } ',
                      'foreach($a as $c) { /**/ } ',
);

$expected_not = array('foreach($a as $b3 => $c3) { /**/ } ',
                      'foreach($a as $b4 => $c4) { /**/ } ',
                      'foreach($a as $b5 => $c5) { /**/ } ',
                      'foreach($a2 as $c2) { /**/ } ',);

?>