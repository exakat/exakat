<?php

$expected     = array('foreach($val as &$a2) { /**/ } ',
                      'foreach($val as &$b) { /**/ } ',
                      'foreach($val as &$e) { /**/ } ',
                      'foreach($val as &$g) { /**/ } ',
                     );

$expected_not = array('foreach($val as &$a) { /**/ } ',
                      'foreach($val as &$d) { /**/ } ',
                      'foreach($val as &$h) { /**/ } ',
                     );

?>