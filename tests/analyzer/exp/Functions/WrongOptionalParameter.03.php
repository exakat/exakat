<?php

$expected     = array('function a0010($b, $c, $d = null, $e) { /**/ } ',
                      'function a10($b = 2, $c) { /**/ } ',
                     );

$expected_not = array('function a0($b) { /**/ } ',
                      'function a1($b = 1) { /**/ } ',
                     );

?>