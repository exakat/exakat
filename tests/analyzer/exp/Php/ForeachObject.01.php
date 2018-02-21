<?php

$expected     = array('foreach($array1 as $o->$b) { /**/ } ',
                     );

$expected_not = array('foreach($array2 as $o->$b) { /**/ } ',
                      'foreach($array3 as $o->$b) { /**/ } ',
                      'foreach($array4 as $o->$b) { /**/ } ',
                     );

?>