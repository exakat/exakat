<?php

$expected     = array('foreach($_REQUEST ?? [ ] as $k5 => $v) { /**/ } ',
                      'foreach(CONFIG ? $_GET : $_POST as $k6 => $v) { /**/ } ',
                      'foreach($args ?? $_POST as $k4 => $v) { /**/ } ',
                      'foreach($_COOKIE as $k3 => $v) { /**/ } ',
                      'foreach($_POST as $k2 => $v) { /**/ } ',
                     );

$expected_not = array('foreach($input as $k1 => $v) { /**/ } ',
                     );

?>