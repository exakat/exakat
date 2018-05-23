<?php

$expected     = array('foreach($a as $b[1] => $c[3]) { /**/ } ', 
                      'foreach($a as $c[5]) { /**/ } '
                     );

$expected_not = array('foreach($a[3] as $c) { /**/ } '
                     );

?>