<?php

$expected     = array('foreach($b as $d) { /**/ } ',
                     );

$expected_not = array('foreach($b as &$d) { /**/ } ',
                      'foreach($a as $b) { /**/ } ',
                     );

?>