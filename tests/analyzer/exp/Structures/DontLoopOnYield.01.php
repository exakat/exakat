<?php

$expected     = array('foreach(generator( ) as $g) { /**/ } ',
                     );

$expected_not = array('for($i = 0; $i < 10; ++$i) { /**/ } ',
                      'foreach(generator3( ) as $g3) { /**/ } ',
                     );

?>