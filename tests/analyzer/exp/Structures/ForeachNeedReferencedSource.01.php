<?php

$expected     = array('foreach(array(1, 2, 3, 4) as &$value) { /**/ } ',
                     );

$expected_not = array('foreach(array(1, 2, 3, 4) as $value) { /**/ } ',
                      'foreach($x as &$value) { /**/ } ',
                     );

?>