<?php

$expected     = array('foreach($c as $d) { /**/ } ', 
                      'foreach($c2 as $d2) { /**/ } ', 
);

$expected_not = array('foreach($a as $b) { /**/ } ', 
                      'do { /**/ }  while (1)');

?>