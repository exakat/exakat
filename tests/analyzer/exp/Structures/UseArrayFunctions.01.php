<?php

$expected     = array('foreach($multiplication as $b) { /**/ } ', 
                      'foreach($addition as $b) { /**/ } ', 
                      'foreach($append as $b) { /**/ } ',
                      'foreach($concatenation as $b) { /**/ } ', 
                      'foreach($ifthen as $b) { /**/ } ', 
                      'foreach($array_push as $b) { /**/ } ', 

                     );

$expected_not = array('foreach($two as $b) { /**/ } ', 
                     );

?>