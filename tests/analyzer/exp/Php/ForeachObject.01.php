<?php

$expected     = array('foreach($array1 as $o->$b) { /**/ } ',
                      'foreach($array2 as $o->$b[1]) { /**/ } ',
                      'foreach($array3 as $o->b) { /**/ } ',
                      'foreach($array6 as $o->$b->$c) { /**/ } ',
                     );

$expected_not = array('foreach($array4 as $o->$b) { /**/ } ',
                      'foreach($array5 as [$o->b] ) { /**/ } ',
                     );

?>