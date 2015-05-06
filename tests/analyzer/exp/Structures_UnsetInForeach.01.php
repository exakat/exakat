<?php

$expected     = array('foreach($a as $unsetC) { /**/ } ', 
                      'foreach($a as $unsetB => $c) { /**/ } ', 
                      'foreach($a as &$unsetRefC) { /**/ } ', 
                      'foreach($a as $b => $unsetC) { /**/ } ', 
                      'foreach($a as $b => &$unsetRefC) { /**/ } ', 
                      'foreach($a as &$unsetArrayC) { /**/ } ', 
                      'foreach($a as $unsetArrayB => $c) { /**/ } ', 
                      'foreach($a as $b => &$unsetArrayC) { /**/ } ');

$expected_not = array();

?>