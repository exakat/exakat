<?php

$expected     = array('foreach($code as $k => $v3) { /**/ } ',
                     );

$expected_not = array('foreach($code as $k => &$v) { /**/ } ',
                      'foreach($code as $k => $v2) { /**/ } ',
                     );

?>