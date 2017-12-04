<?php

$expected     = array('foreach($code as $v3) { /**/ } ',
                     );

$expected_not = array('foreach($code as &$v) { /**/ } ',
                      'foreach($code as $v2) { /**/ } ',
                     );

?>