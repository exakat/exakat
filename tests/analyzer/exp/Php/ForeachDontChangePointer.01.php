<?php

$expected     = array('foreach($array as &$val) { /**/ } ',
                      'foreach($array2 as &$val) { /**/ } ',
                      'foreach($array3 as $key => &$val) { /**/ } ',
                     );

$expected_not = array('foreach($array4 as &$val) { /**/ } ',
                      'foreach($array5 as $key => &$val) { /**/ } ',
                     );

?>